<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Loan extends Model {
 protected $table = 'loans';
 public $timestamps = false;
 protected $guarded = [];
 protected $hidden = ['password_hash','token_hash'];
 protected function casts(): array { return ['id'=>'integer','user_id'=>'integer','equipment_id'=>'integer']; }
public function user() { return $this->belongsTo(User::class); }
public function equipment() { return $this->belongsTo(Equipment::class); }
}
