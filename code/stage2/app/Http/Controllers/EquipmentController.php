<?php
namespace App\Http\Controllers;
use App\Models\{User,Equipment,Loan,Token};
use App\Exceptions\ContractError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class EquipmentController extends Controller {
 private function fail(int $status,string $code,string $message): never { throw new ContractError($status,$code,$message); }
 private function body(Request $r): array {
  try { $body=json_decode($r->getContent(),false,32,JSON_THROW_ON_ERROR); }
  catch(\JsonException) { $this->fail(400,'invalid_json','Send a valid JSON object.'); }
  if(!$body instanceof \stdClass) $this->fail(400,'invalid_json','Send a valid JSON object.');
  return (array)$body;
 }
 private function text(array $b,string $key,int $min,int $max,bool $trim=true): string {
  $v=$b[$key]??null;
  if(is_string($v)) { $v=$trim?trim($v):$v; if(strlen($v)>=$min && strlen($v)<=$max && !str_contains($v,"\0")) return $v; }
  $this->fail(400,'invalid_input',"$key must be a string of $min to $max bytes.");
 }
 private function email(array $b): string {
  $v=strtolower($this->text($b,'email',1,255));
  if(!filter_var($v,FILTER_VALIDATE_EMAIL)) $this->fail(400,'invalid_email','Enter a valid email address.');
  return $v;
 }
 private function actor(Request $r,?string $role=null): User {
  if(!preg_match('/^Bearer ([A-Za-z0-9_-]{1,256})$/Di',$r->header('Authorization',''),$m)) $this->fail(401,'authentication_required','Send a valid bearer token.');
  $token=Token::with('user')->whereKey(hash('sha256',$m[1]))->where('expires_at','>',now('UTC'))->first();
  if(!$token || !$token->user) $this->fail(401,'invalid_token','The bearer token is invalid or expired.');
  if($role!==null && $token->user->role!==$role) $this->fail(403,'forbidden','This account cannot perform that action.');
  return $token->user;
 }
 private function item(int $id,bool $lock=false): Equipment {
  $q=Equipment::query(); if($lock) $q->lockForUpdate();
  $e=$q->find($id); if(!$e) $this->fail(404,'equipment_not_found','Equipment does not exist.'); return $e;
 }
 private function itemData(Equipment $e): array { return ['id'=>$e->id,'name'=>$e->name,'description'=>$e->description,'available'=>!$e->activeLoans()->exists()]; }
 private function loanData(Loan $l): array { return $l->only(['id','user_id','equipment_id','borrowed_at','returned_at']); }
 public function register(Request $r) {
  $b=$this->body($r); $name=$this->text($b,'name',1,100); $email=$this->email($b); $password=$this->text($b,'password',8,72,false);
  try { $u=User::create(['name'=>$name,'email'=>$email,'password_hash'=>password_hash($password,PASSWORD_BCRYPT),'role'=>'borrower']); }
  catch(QueryException $e) { if(($e->errorInfo[1]??null)===1062) $this->fail(409,'email_exists','This email is already registered.'); throw $e; }
  return response()->json(['data'=>$u->only(['id','name','email','role'])],201);
 }
 public function login(Request $r) {
  $b=$this->body($r); $u=User::where('email',$this->email($b))->first(); $password=$this->text($b,'password',1,72,false);
  if(!$u || !password_verify($password,$u->password_hash)) $this->fail(401,'invalid_credentials','Email or password is incorrect.');
  $plain=bin2hex(random_bytes(32));
  $u->tokens()->create(['token_hash'=>hash('sha256',$plain),'expires_at'=>now('UTC')->addDay()->format('Y-m-d H:i:s')]);
  return response()->json(['data'=>['token'=>$plain,'token_type'=>'Bearer']])->header('Cache-Control','no-store');
 }
 public function catalog(Request $r) {
  $filter=$r->query('available');
  if($filter!==null && !in_array($filter,['true','false'],true)) $this->fail(400,'invalid_filter','Availability must be true or false.');
  $q=Equipment::orderBy('id');
  if($filter==='true') $q->whereDoesntHave('activeLoans');
  if($filter==='false') $q->whereHas('activeLoans');
  return ['data'=>$q->get()->map(fn($e)=>$this->itemData($e))->all()];
 }
 public function equipment(int $id) { return ['data'=>$this->itemData($this->item($id))]; }
 private function save(Request $r,?int $id): array {
  $b=$this->body($r); $values=['name'=>$this->text($b,'name',1,100),'description'=>$this->text($b+['description'=>''],'description',0,1000)];
  return DB::transaction(function() use($id,$values) {
   $e=$id===null?Equipment::create($values):$this->item($id,true);
   if($id!==null) { $e->fill($values); $e->save(); }
   return $this->itemData($e);
  });
 }
 public function createEquipment(Request $r) { $this->actor($r,'staff'); return response()->json(['data'=>$this->save($r,null)],201); }
 public function updateEquipment(Request $r,int $id) { $this->actor($r,'staff'); return ['data'=>$this->save($r,$id)]; }
 public function deleteEquipment(Request $r,int $id) {
  $this->actor($r,'staff');
  DB::transaction(function() use($id) { $e=$this->item($id,true); if($e->loans()->exists()) $this->fail(409,'loan_history','Equipment with loan history cannot be deleted.'); $e->delete(); });
  return response()->noContent();
 }
 public function loans(Request $r) {
  $u=$this->actor($r); $q=$u->role==='staff'?Loan::query():$u->loans();
  return ['data'=>$q->orderBy('id')->get()->map(fn($l)=>$this->loanData($l))->all()];
 }
 public function checkout(Request $r) {
  $u=$this->actor($r,'borrower'); $id=$this->body($r)['equipment_id']??null;
  if(!is_int($id) || $id<1) $this->fail(400,'invalid_input','equipment_id must be a positive JSON integer.');
  $data=DB::transaction(function() use($u,$id) {
   $e=$this->item($id,true);
   if($e->activeLoans()->lockForUpdate()->first()) $this->fail(409,'equipment_unavailable','Equipment is already on loan.');
   return $this->loanData($u->loans()->create(['equipment_id'=>$id,'borrowed_at'=>now('UTC')->format('Y-m-d H:i:s')])->refresh());
  }); return response()->json(['data'=>$data],201);
 }
 public function returnLoan(Request $r,int $id) {
  $u=$this->actor($r);
  $data=DB::transaction(function() use($u,$id) {
   $l=Loan::lockForUpdate()->find($id);
   if(!$l) $this->fail(404,'loan_not_found','Loan does not exist.');
   if($u->role!=='staff' && $u->id!==$l->user_id) $this->fail(403,'forbidden','Only the borrower or staff can return this loan.');
   if($l->returned_at!==null) $this->fail(409,'already_returned','This loan was already returned.');
   $l->returned_at=now('UTC')->format('Y-m-d H:i:s'); $l->save(); return $this->loanData($l);
  }); return ['data'=>$data];
 }
}
