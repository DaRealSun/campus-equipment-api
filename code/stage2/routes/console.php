<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\{User,Equipment,Loan,Token};
Artisan::command('fixtures:reset',function(){
 if(getenv('ALLOW_FIXTURE_RESET')!=='1' || config('database.connections.mysql.database')!=='csc640_equipment_stage2') { $this->error('Requires ALLOW_FIXTURE_RESET=1 and the isolated Stage 2 database.'); return 1; }
 DB::transaction(function(){
  foreach([Token::class,Loan::class,Equipment::class,User::class] as $m) $m::query()->delete();
  foreach([[101,'Staff','staff@example.test','staff'],[102,'Borrower One','borrower1@example.test','borrower'],[103,'Borrower Two','borrower2@example.test','borrower']] as [$id,$name,$email,$role]) {
   User::create(['id'=>$id,'name'=>$name,'email'=>$email,'role'=>$role,'password_hash'=>password_hash('CourseFixturePassword-42',PASSWORD_BCRYPT)]);
  }
  Equipment::create(['id'=>1001,'name'=>'Tripod','description'=>'Available fixture']);
  Equipment::create(['id'=>1002,'name'=>'Camera','description'=>'Loaned fixture']);
  foreach([101=>'fixture-staff-token',102=>'fixture-borrower1-token',103=>'fixture-borrower2-token'] as $id=>$plain) Token::create(['token_hash'=>hash('sha256',$plain),'user_id'=>$id,'expires_at'=>now('UTC')->addDay()->format('Y-m-d H:i:s')]);
  Loan::create(['id'=>2001,'user_id'=>103,'equipment_id'=>1002,'borrowed_at'=>now('UTC')->format('Y-m-d H:i:s')]);
 }); $this->info('Synthetic Stage 2 fixtures reset.');
})->purpose('Reset isolated synthetic fixtures only');
