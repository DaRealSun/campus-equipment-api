<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('users',function(Blueprint $t){$t->id();$t->string('name',100);$t->string('email')->unique();$t->string('password_hash');$t->enum('role',['borrower','staff'])->default('borrower');});
  Schema::create('equipment',function(Blueprint $t){$t->id();$t->string('name',100);$t->string('description',1000)->default('');});
  Schema::create('loans',function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained('users');$t->foreignId('equipment_id')->constrained('equipment');$t->dateTime('borrowed_at');$t->dateTime('returned_at')->nullable();});
  Schema::create('tokens',function(Blueprint $t){$t->char('token_hash',64)->primary();$t->foreignId('user_id')->constrained('users');$t->dateTime('expires_at');});
 }
 public function down(): void { foreach(['tokens','loans','equipment','users'] as $name) Schema::dropIfExists($name); }
};
