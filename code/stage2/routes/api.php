<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController as C;
Route::prefix('v1')->group(function() {
 Route::post('auth/register',[C::class,'register']);
 Route::post('auth/login',[C::class,'login']);
 Route::get('equipment',[C::class,'catalog']);
 Route::post('equipment',[C::class,'createEquipment']);
 Route::get('equipment/{id}',[C::class,'equipment'])->whereNumber('id');
 Route::put('equipment/{id}',[C::class,'updateEquipment'])->whereNumber('id');
 Route::delete('equipment/{id}',[C::class,'deleteEquipment'])->whereNumber('id');
 Route::get('loans',[C::class,'loans']);
 Route::post('loans',[C::class,'checkout']);
 Route::put('loans/{id}/return',[C::class,'returnLoan'])->whereNumber('id');
});
