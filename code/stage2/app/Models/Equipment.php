<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Equipment extends Model {
 protected $table = 'equipment';
 public $timestamps = false;
 protected $guarded = [];
 protected $hidden = ['password_hash','token_hash'];
 public function loans() { return $this->hasMany(Loan::class); }
public function activeLoans() { return $this->loans()->whereNull('returned_at'); }
}
