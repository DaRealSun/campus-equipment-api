<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Token extends Model {
 protected $table = 'tokens';
 public $timestamps = false;
 protected $guarded = [];
 protected $hidden = ['password_hash','token_hash'];
 protected $primaryKey = 'token_hash';
public $incrementing = false;
protected $keyType = 'string';
public function user() { return $this->belongsTo(User::class); }
}
