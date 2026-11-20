<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class User extends Model {
 protected $table = 'users';
 public $timestamps = false;
 protected $guarded = [];
 protected $hidden = ['password_hash','token_hash'];
 public function loans() { return $this->hasMany(Loan::class); }
public function tokens() { return $this->hasMany(Token::class); }
}
