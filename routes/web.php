<?php 
use App\Http\Controllers\AdminController;  
use App\Http\Controllers\ApiController;  
use OpenApi\Generator as OpenApiGenerator;

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
    return view('welcome');
});

Auth::routes();


Route::get('products', [AdminController::class, 'products']);
Route::get('products/add', [AdminController::class, 'addproducts']);
Route::post('products/save', [AdminController::class, 'storeproduct'])->name('products.store');
Route::get('products/edit/{id}', [AdminController::class, 'editproducts']);
Route::post('products/update', [AdminController::class, 'updateproduct'])->name('products.update');
Route::get('products/delete/{id}', [AdminController::class, 'deleteproducts']);
Route::get('products/show/{id}', [AdminController::class, 'showproduct'])->name('products.show');
Route::post('products/api/add', [ApiController::class, 'addapiproducts']);
Route::get('products/api/get', [ApiController::class, 'getapiproducts']);
Route::get('products/api/delete/{id}', [ApiController::class, 'deleteapiproducts']);
Route::get('products/api/show/{id}', [ApiController::class, 'showeapiproducts']);
Route::post('products/api/update', [ApiController::class, 'updateeapiproducts']);



Route::get('/products/api/docs', function () {
    return view('swagger');
});
Route::get('/products/api/swagger-json', function () {
    $openApi = new OpenApiGenerator();
    $sources = [base_path('app')]; // You may need to add more paths if necessary

    return response()->json($openApi->generate($sources), 200, [], JSON_UNESCAPED_SLASHES);
});