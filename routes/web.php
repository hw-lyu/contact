<?php

use Illuminate\Support\Facades\Route;
use App\Models\Contact;
use App\Http\Controllers\ContactControllers;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * 메인
 */
Route::get('/', function() {
    return redirect('contact');
});

/**
 * 목록
 */
Route::get('/contact/{id?}/{offset?}', [ContactControllers::class, 'index'])
    ->where('id', '[가-힣]+')
    ->where('offset', '[0-9]+')
    ->name('contact');

/**
 * 등록
 */
Route::post('/list', [ContactControllers::class, 'registration']);

/**
 * 검색
 */
Route::post('/search', [ContactControllers::class, 'search']);

/**
 * 저장
 */
Route::patch('/save', [ContactControllers::class, 'save']);

/**
 * 삭제
 */
Route::delete('/delete', [ContactControllers::class, 'delete']);

