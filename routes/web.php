<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurrencyValueController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProcessRateController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use App\Models\Material;
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

// $materials = Material::all();
$materials = [
    [
        'id' => 1,
        'name' => 'Resin'
    ],
    [
        'id' => 2,
        'name' => 'Steel'
    ],
    [
        'id' => 3,
        'name' => 'Urethane'
    ],
    [
        'id' => 4,
        'name' => 'Fabric'
    ],
];

$currency = [
    [
        'id' => 1,
        'name' => 'IDR',
    ],
    [
        'id' => 1,
        'name' => 'USD',
    ],
    [
        'id' => 1,
        'name' => 'JPY',
    ],
    [
        'id' => 1,
        'name' => 'THB',
    ],
];

$currency_type = [
    [
        'id' => 1,
        'name' => 'slide'
    ],
    [
        'id' => 2,
        'name' => 'non-slide'
    ],
];

// authentication
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// error page unauthorized
Route::get('/dont-have-permission', function() {
    return view('dont-have-permission');
})->name('dont-have-permission');

// material
Route::get('/material/download/template', [MaterialController::class, 'downloadTemplate'])->name('material.download-template');
Route::get('/process/download/template', [ProcessRateController::class, 'downloadTemplate'])->name('process.download-template');
Route::get('/currency/download/template', [CurrencyValueController::class, 'downloadTemplate'])->name('currency.download-template');

Route::prefix('admin')->middleware('auth')->group(function() use ($materials, $currency_type, $currency) {
    // logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // material
    Route::get('/material/data/ajax/{type}', [MaterialController::class, 'ajax'])->name('material.ajax');
    Route::post('/material/import', [MaterialController::class, 'import'])->name('material.import');
    Route::post('/material/submit-import', [MaterialController::class, 'submitImport'])->name('material.submit-import');
    Route::post('/material/search/spec', [MaterialController::class, 'searchSpec'])->name('material.search-spec');
    foreach ($materials as $material) {
        Route::get('material/' . $material['name'] . '/{type}', [MaterialController::class, 'index'])->name('material.' . $material['name']);
        Route::post('material/{type}/store', [MaterialController::class, 'store'])->name('material.store');
        Route::get('material/{type}/{id}/edit', [MaterialController::class, 'edit'])->name('material.edit');
        Route::put('material/{id}', [MaterialController::class, 'update'])->name('material.update');
        Route::delete('material/{id}', [MaterialController::class, 'destroy'])->name('material.destroy');
    }

    // Process
    Route::post('/process/search/spec', [ProcessRateController::class, 'searchSpec'])->name('process.search-spec');
    Route::post('/process/import', [ProcessRateController::class, 'import'])->name('process.import');
    Route::get('/process/ajax', [ProcessRateController::class, 'ajax'])->name('process.ajax');
    Route::post('/process/submit-import', [ProcessRateController::class, 'submitImport'])->name('process.submit-import');
    Route::resource('process', ProcessRateController::class);

    // currency
    Route::get('/currency/data/ajax/{type}/{group}', [CurrencyValueController::class, 'ajax'])->name('currency.ajax');
    Route::post('/currency/import/{type}/{group}', [CurrencyValueController::class, 'import'])->name('currency.import');
    Route::post('/currency/submit-import/{type}/{group}', [CurrencyValueController::class, 'submitImport'])->name('currency.submit-import');
    Route::post('currency/main/{type}/{group}/store', [CurrencyValueController::class, 'store'])->name('currency.store');
    Route::get('currency/main/{type}/{group}/{id}/show', [CurrencyValueController::class, 'show'])->name('currency.show');
    Route::put('currency/main/{type}/{group}/{id}/update', [CurrencyValueController::class, 'update'])->name('currency.update');
    Route::delete('currency/main/{id}', [CurrencyValueController::class, 'destroy'])->name('currency.destroy');
    foreach ($currency_type as $type) {
        foreach ($currency as $group) {
            Route::get('currency/main/' . $type['name'] . '/' . $group['name'], [CurrencyValueController::class, 'index'])->name('currency.' . $type['name'] . '.' . $group['name']);
            Route::get('currency/main/'.$type['name'] . '/' . $group['name'] .'/{id}/edit', [CurrencyValueController::class, 'edit'])->name('currency.edit');
        }
    }
    
    // role and permission
    Route::get('/permissions', [RolePermissionController::class, 'permissions'])->name('setting.permissions');
    Route::get('/permissions/ajax', [RolePermissionController::class, 'ajaxPermission'])->name('setting.permissions.ajax');
    Route::post('/permissions', [RolePermissionController::class, 'storePermission'])->name('setting.permissions.store');
    Route::get('/permissions/{id}', [RolePermissionController::class, 'editPermission'])->name('setting.permissions.edit');
    Route::put('/permissions/{id}', [RolePermissionController::class, 'updatePermission'])->name('setting.permissions.update');
    Route::get('/roles', [RolePermissionController::class, 'roles'])->name('setting.roles');
    Route::get('/roles/ajax', [RolePermissionController::class, 'ajaxRoles'])->name('setting.roles.ajax');
    Route::post('/roles', [RolePermissionController::class, 'storeRole'])->name('setting.roles.store');
    Route::get('/roles/{id}', [RolePermissionController::class, 'showRoles'])->name('setting.roles.show');
    Route::put('/roles/{id}', [RolePermissionController::class, 'updateRoles'])->name('setting.roles.update');
    Route::delete('/roles/{id}', [RolePermissionController::class, 'destroyRole'])->name('setting.roles.destroy');

    // user
    Route::get('/users/ajax', [UserController::class, 'ajax'])->name('users.ajax');
    Route::resource('users', UserController::class);
});
