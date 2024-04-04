<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\BrandProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SkuController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::post('/change-language', [LanguageController::class, 'changeLanguage'])->name('change_language');

// Dashboard routes
Route::group(['middleware' => 'auth', 'prefix' => '/'], function () {

    // Home page route
    Route::get('', function () {
        return view('dashboard.index');
    });
    // Dashboard route
    Route::get('dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
    // Home page route
    Route::get('home', function () {
        return view('dashboard.index');
    });
    // Users routes
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('users')->middleware('permission:user_view');
        Route::get('/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:user_create');
        Route::post('/store', [UserController::class, 'store'])->name('users.store')->middleware('permission:user_create');
        Route::get('/{id}/show', [UserController::class, 'show'])->name('users.show')->middleware('permission:user_view');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:user_update');
        Route::post('/{id}/update', [UserController::class, 'update'])->name('users.update')->middleware('permission:user_update');
        Route::post('/{id}/delete', [UserController::class, 'destroy'])->name('users.delete')->middleware('permission:user_delete');
    });
    // Roles & Permissions routes
    Route::group(['middleware' => 'role:admin|manager', 'prefix' => 'acl'], function () {
        // Permission module
        Route::get('/permissions/modules', [PermissionController::class, 'permissionModules'])->name('permission_modules');
        Route::get('/permissions/modules/create', [PermissionController::class, 'createPermissionModule'])->name('permission_modules.create');
        Route::post('/permissions/modules/store', [PermissionController::class, 'savePermissionModule'])->name('permission_modules.store');
        // Roles
        Route::get('/roles', [RoleController::class, 'index'])->name('roles')->middleware('permission:role_view');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:role_create');
        Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:role_create');
        Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:role_update');
        Route::post('/roles/{id}/update', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:role_update');
        Route::post('/roles/{id}/delete', [RoleController::class, 'destroy'])->name('roles.delete')->middleware('permission:role_delete');
        // Permissions
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions')->middleware('permission:permission_view');
        Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit')->middleware('permission:permission_update');
        Route::get('/permissions/{id}/update', [PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:permission_update');
        Route::get('/permissions/{id}/delete', [PermissionController::class, 'destroy'])->name('permissions.delete')->middleware('permission:permission_delete');
    });
    // Category routes
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories')->middleware('permission:category_view');
        Route::get('/create', [CategoryController::class, 'create'])->name('categories.create')->middleware('permission:category_create');
        Route::post('/store', [CategoryController::class, 'store'])->name('categories.store')->middleware('permission:category_create');
        Route::get('/{id}/show', [CategoryController::class, 'show'])->name('categories.show')->middleware('permission:category_view');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit')->middleware('permission:category_update');
        Route::post('/{id}/update', [CategoryController::class, 'update'])->name('categories.update')->middleware('permission:category_update');
        Route::post('/{id}/delete', [CategoryController::class, 'destroy'])->name('categories.delete')->middleware('permission:category_delete');
    });
    // Product routes
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [ProductController::class, 'index'])->name('products')->middleware('permission:product_view');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create')->middleware('permission:product_view');
        Route::post('/store', [ProductController::class, 'store'])->name('products.store')->middleware('permission:product_view');
        Route::get('/{id}/show', [ProductController::class, 'show'])->name('products.show')->middleware('permission:product_view');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('permission:product_view');
        Route::post('/{id}/update', [ProductController::class, 'update'])->name('products.update')->middleware('permission:product_view');
        Route::post('/{id}/delete', [ProductController::class, 'destroy'])->name('products.delete')->middleware('permission:product_view');
    });
    // Brand routes
    Route::group(['prefix' => 'brands'], function () {
        Route::get('/', [BrandController::class, 'index'])->name('brands')->middleware('permission:brand_view');
        Route::get('/create', [BrandController::class, 'create'])->name('brands.create')->middleware('permission:brand_create');
        Route::post('/store', [BrandController::class, 'store'])->name('brands.store')->middleware('permission:brand_create');
        Route::get('/{id}/show', [BrandController::class, 'show'])->name('brands.show')->middleware('permission:brand_view');
        Route::get('/{id}/edit', [BrandController::class, 'edit'])->name('brands.edit')->middleware('permission:brand_update');
        Route::post('/{id}/update', [BrandController::class, 'update'])->name('brands.update')->middleware('permission:brand_update');
        Route::post('/{id}/delete', [BrandController::class, 'destroy'])->name('brands.delete')->middleware('permission:brand_delete');
        // brand products
        Route::get('/{id}/products', [BrandProductController::class, 'brandProducts'])->name('brand.products')->middleware('permission:brand_view');
        Route::post('/{id}/products/create', [BrandProductController::class, 'createBrandProducts'])->name('brand.products.create')->middleware('permission:brand_create');
        Route::post('/{brand_id}/products/delete', [BrandProductController::class, 'deleteBrandProduct'])->name('brand.products.delete')->middleware('permission:brand_delete');
        // get brand products
        Route::post('/{id}/get-brand-products', [BrandProductController::class, 'getBrandProducts'])->name('brand.get_brand_products')->middleware('permission:brand_view');
    });
    // SKUs routes
    Route::group(['prefix' => 'skus'], function () {
        Route::get('/', [SkuController::class, 'index'])->name('skus')->middleware('permission:sku_view');
        Route::get('/create', [SkuController::class, 'create'])->name('skus.create')->middleware('permission:sku_view');
        Route::post('/store', [SkuController::class, 'store'])->name('skus.store')->middleware('permission:sku_view');
        Route::get('/{id}/show', [SkuController::class, 'show'])->name('skus.show')->middleware('permission:sku_view');
        Route::get('/{id}/edit', [SkuController::class, 'edit'])->name('skus.edit')->middleware('permission:sku_view');
        Route::post('/{id}/update', [SkuController::class, 'update'])->name('skus.update')->middleware('permission:sku_view');
        Route::post('/{id}/delete', [SkuController::class, 'destroy'])->name('skus.delete')->middleware('permission:sku_view');
    });
    // Units routes
    Route::group(['prefix' => 'units'], function () {
        Route::get('/', [UnitController::class, 'index'])->name('units')->middleware('permission:unit_view');
        Route::get('/create', [UnitController::class, 'create'])->name('units.create')->middleware('permission:unit_view');
        Route::post('/store', [UnitController::class, 'store'])->name('units.store')->middleware('permission:unit_view');
        Route::get('/{id}/show', [UnitController::class, 'show'])->name('units.show')->middleware('permission:unit_view');
        Route::get('/{id}/edit', [UnitController::class, 'edit'])->name('units.edit')->middleware('permission:unit_view');
        Route::post('/{id}/update', [UnitController::class, 'update'])->name('units.update')->middleware('permission:unit_view');
        Route::post('/{id}/delete', [UnitController::class, 'destroy'])->name('units.delete')->middleware('permission:unit_view');
    });

    // Filter Routing //
    Route::post('seller/{seller_id}/brands', [BrandController::class, 'sellerBrands'])->name('seller_brands')->middleware('permission:brand_view');
});

// Users routes
Route::post('/users/register', [UserController::class, 'store'])->name('users.register');
