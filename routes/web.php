<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('blog\\index');
});

Route::get('/post/{id}', function () {
    return view('blog\\post');
})->name('post');

Route::get('/admin', function () {
    return view('admin\\index');
})->name('admin.index');

Route::get('/admin/create', function () {
    return view('admin\\create');
})->name('admin.create');

Route::get('/admin/edit/{id}', function () {
    return view('admin\\edit');
})->name('admin.edit');

Route::get('/about', function () {
    return view('other\\about');
})->name('about');

Route::post('create',function(\Illuminate\Http\Request $request, \Illuminate\Validation\Factory $validator){
    $validation=$validator->make($request->all(),[
        'title'=> 'required|min:5',
        'content'=>'required|min:10'
    ]);
    if($validation->fails()){
        return redirect()->back()->withErrors($validation);
    }
    return redirect()->route('admin.index')
        ->with('info','Post created, Title: '.$request->input('title'));
})->name('admin.create');


Route::post('edit', function (\Illuminate\Http\Request $request ,
                                  \Illuminate\Validation\Factory $validator){
    $validation = $validator->make($request->all(), [
        'title' => 'required|min:5',
        'content' => 'required|min:10'
    ]);
    if ( $validation->fails()) {
        return redirect()->back()->withErrors($validation);
    }
    return redirect()
        ->route('admin.index')
        ->with('info','Post edited, new Title: '.$request->input('title'));
})->name('admin.update');


Route::group([ 'prefix' => 'admin'], function() {
    Route::get ('', [
        'uses' => '\App\Http\Controllers\PostController@getAdminIndex',
        'as' => 'admin.index'
    ]);
    Route :: get ('create ', [
        'uses' => '\App\Http\Controllers\PostController@getAdminCreate',
        'as' => 'admin.create'
    ]);
    Route::post('create', [
        'uses' => '\App\Http\Controllers\PostController@postAdminCreate',
        'as' => 'admin.create'
    ]);
    Route::get('edit/{ id}', [
        'uses' => '\App\Http\Controllers\PostController@getAdminEdit',
        'as' => 'admin.edit'
    ]);
    Route::post('edit',[
        'uses' => '\App\Http\Controllers\PostController@postAdminUpdate',
        'as' => 'admin.update'
    ]);
});

Route::get ('/', [
    'uses' => '\App\Http\Controllers\PostController@getIndex',
    'as' => 'blog.index'
]);
Route::get('post/{ id}', [
    'uses' => '\App\Http\Controllers\PostController@getPost',
    'as' => 'blog.post'
]);

