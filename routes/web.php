<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('livewire.js',function(Request $request){
	$newRequest = Request::create('/livewire/livewire.js', 'GET');
	$response = app()->handle($newRequest);

					                                                    return $response;
});
Route::get('livewire.js',function(Request $request){
	                        $newRequest = Request::create('/livewire/livewire.js', 'GET');
			                               $response = app()->handle($newRequest);
			                            return $response;	
});
        Route::post('/upload-file', function (Request $request) {
		    $postData = $request->all();
		        $newRequest = Request::create('/livewire/upload-file', 'POST', $postData);
		        $response = app()->handle($newRequest);
			    return $response;
	});

	Route::post('/update', function (Request $request) {
    $postData = $request->all();
    $newRequest = Request::create('/livewire/update', 'POST', $postData);
    $response = app()->handle($newRequest);

	$content = $response->getContent();
	$newContent = str_replace('/admin', '/admin/admin', $content);

	 $newContent = str_replace('localhost', 'risman.app', $newContent);
	$response->setContent($newContent);
    return $response;
});
Route::get('/', function () {
    return view('welcome');
});
