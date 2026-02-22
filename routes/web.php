<?php

use App\Http\Controllers\AssetLifecycleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\PicController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware([AuthMiddleware::class])->group(function () {
    Route::prefix('/dashboard')->controller(DashboardController::class)->group(function () {
        Route::get('/', 'dashboard')->name('dashboard');
        Route::get('/stock-availability', 'stockAvailability')->name('stockAvailability');
        Route::get('/inbound-vs-return', 'inboundVsReturn')->name('inboundVsReturn');
        Route::get('/top-devices', 'topDevices')->name('topDevices');
        Route::get('/lifecycle-status-distributor', 'lifecycleStatusDistributor')->name('lifecycleStatusDistributor');
    });

    Route::prefix('/inbound')->controller(InboundController::class)->group(function () {
        Route::prefix('/purchase-order')->group(function () {
            Route::get('/', 'index')->name('inbound.receiving.index');
            Route::get('/create', 'create')->name('inbound.receiving.create');
            Route::post('/store', 'store')->name('inbound.receiving.store');
            Route::get('/bulk-import', 'bulkImport')->name('inbound.receiving.bulkImport');
            Route::post('/bulk-import', 'bulkImportStore')->name('inbound.receiving.bulkImport.store');
            Route::get('/detail', 'detail')->name('inbound.receiving.detail');

            Route::post('/change-status', 'changeStatus')->name('inbound.receiving.changeStatus');

            Route::get('/download-excel', 'downloadExcel')->name('inbound.receiving.downloadExcel');
            Route::get('/download-pdf', 'downloadPDF')->name('inbound.receiving.downloadPDF');
        });

        Route::prefix('/put-away')->group(function () {
            Route::get('/', 'putAway')->name('inbound.putAway.index');
            Route::get('/process', 'putAwayProcess')->name('inbound.putAway.process');
            Route::get('/detail', 'putAwayDetail')->name('inbound.putAway.detail');
            Route::post('/store', 'putAwayStore')->name('inbound.putAway.store');
        });
    });

    Route::prefix('/inventory')->controller(InventoryController::class)->group(function () {
        Route::get('/', 'index')->name('inbound.inventory.index');
        Route::get('/history', 'history')->name('inbound.inventory.history');
        Route::get('/cycle-count', 'cycleCount')->name('inventory.cycleCount');

        Route::prefix('/stock-movement')->group(function () {
            Route::get('/', 'stockMovement')->name('inventory.stockMovement.index');
            Route::get('/create', 'create')->name('inventory.stockMovement.create');
            Route::post('/store', 'store')->name('inventory.stockMovement.store');
        });

        Route::get('/download-excel', 'downloadExcel')->name('inventory.downloadExcel');
        Route::get('/download-pdf', 'downloadPDF')->name('inventory.downloadPDF');
    });

    Route::prefix('/asset-lifecycle')->controller(AssetLifecycleController::class)->group(function () {
        Route::get('/', 'index')->name('assetLifecycle.index');
        Route::get('/detail', 'detail')->name('assetLifecycle.detail');
        Route::post('/update', 'update')->name('assetLifecycle.update');
        Route::get('/mass-edit', 'massEdit')->name('assetLifecycle.massEdit');
        Route::get('/mass-edit/download-template', 'downloadTemplate')->name('assetLifecycle.downloadTemplate');
        Route::post('/mass-edit/upload-excel', 'uploadExcel')->name('assetLifecycle.uploadExcel');
        Route::post('/mass-edit/process', 'processMassEdit')->name('assetLifecycle.processMassEdit');
    });

    Route::prefix('/outbound')->controller(OutboundController::class)->group(function () {
        Route::get('/', 'index')->name('outbound.index');
        Route::get('/create', 'create')->name('outbound.create');
        Route::get('/inventory-search', 'searchInventory')->name('outbound.inventory.search');
        Route::post('/store', 'store')->name('outbound.store');
        Route::get('/detail', 'detail')->name('outbound.detail');

        Route::get('/download-excel', 'downloadExcel')->name('outbound.downloadExcel');
        Route::get('/download-pdf', 'downloadPDF')->name('outbound.downloadPDF');
    });

    Route::prefix('/return')->controller(ReturnController::class)->group(function () {
        Route::get('/', 'index')->name('return.index');
        Route::get('/create', 'create')->name('return.create');
        Route::get('/inventory-search', 'searchInventory')->name('return.inventory.search');
        Route::post('/store', 'store')->name('return.store');
        Route::get('/detail', 'detail')->name('return.detail');

        Route::get('/download-excel', 'downloadExcel')->name('return.downloadExcel');
        Route::get('/download-pdf', 'downloadPDF')->name('return.downloadPDF');
    });

    Route::prefix('/storage')->controller(StorageController::class)->group(function () {
        Route::prefix('/area')->group(function () {
            Route::get('/', 'area')->name('storage.area');
            Route::post('/store', 'areaStore')->name('storage.area.store');
        });

        Route::prefix('/rak')->group(function () {
            Route::get('/', 'rak')->name('storage.rak');
            Route::post('/store', 'rakStore')->name('storage.rak.store');
            Route::get('/find', 'rakFind')->name('storage.rak.find');
        });

        Route::prefix('/lantai')->group(function () {
            Route::get('/', 'lantai')->name('storage.lantai');
            Route::post('/store', 'lantaiStore')->name('storage.lantai.store');
            Route::get('/find', 'lantaiFind')->name('storage.lantai.find');
        });

        Route::prefix('/bin')->group(function () {
            Route::get('/', 'bin')->name('storage.bin');
            Route::post('/store', 'binStore')->name('storage.bin.store');
            Route::get('/find', 'binFind')->name('storage.bin.find');
        });
    });

    Route::prefix('/user')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('user.index');
        Route::get('/create', 'create')->name('user.create');
        Route::post('/store', 'store')->name('user.store');
        Route::get('/edit', 'edit')->name('user.edit');
        Route::post('/update', 'update')->name('user.update');

        Route::prefix('/menu')->group(function () {
            Route::get('/', 'menu')->name('user.menu');
            Route::post('/store', 'menuStore')->name('user.menu.store');
        });
    });

    Route::prefix('/client')->controller(ClientController::class)->group(function () {
        Route::get('/', 'index')->name('client.index');
        Route::post('/store', 'store')->name('client.store');
    });

    Route::prefix('/pic')->controller(PicController::class)->group(function () {
        Route::get('/', 'index')->name('pic.index');
        Route::post('/store', 'store')->name('pic.store');
        Route::post('/update/{id}', 'update')->name('pic.update');
        Route::post('/destroy/{id}', 'destroy')->name('pic.destroy');
    });
});
