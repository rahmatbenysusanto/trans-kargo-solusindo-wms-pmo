<?php

use App\Http\Controllers\AssetLifecycleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\StorageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

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
        Route::get('/detail', 'detail')->name('inbound.receiving.detail');

        Route::post('/change-status', 'changeStatus')->name('inbound.receiving.changeStatus');
    });

    Route::prefix('/put-away')->group(function () {
        Route::get('/', 'putAway')->name('inbound.putAway.index');
        Route::get('/process', 'putAwayProcess')->name('inbound.putAway.process');
        Route::post('/store', 'putAwayStore')->name('inbound.putAway.store');
    });
});

Route::prefix('/inventory')->controller(InventoryController::class)->group(function () {
    Route::get('/', 'index')->name('inbound.inventory.index');
    Route::get('/history', 'history')->name('inbound.inventory.history');

    Route::prefix('/stock-movement')->group(function () {
        Route::get('/', 'stockMovement')->name('inventory.stockMovement.index');
        Route::get('/create', 'create')->name('inventory.stockMovement.create');
        Route::post('/store', 'store')->name('inventory.stockMovement.store');
    });
});

Route::prefix('/asset-lifecycle')->controller(AssetLifecycleController::class)->group(function () {
    Route::get('/', 'index')->name('assetLifecycle.index');
    Route::get('/detail', 'detail')->name('assetLifecycle.detail');
    Route::post('/update', 'update')->name('assetLifecycle.update');
});

Route::prefix('/outbound')->controller(OutboundController::class)->group(function () {
    Route::get('/', 'index')->name('outbound.index');
    Route::get('/create', 'create')->name('outbound.create');
    Route::post('/store', 'store')->name('outbound.store');
    Route::get('/detail', 'detail')->name('outbound.detail');
});

Route::prefix('/return')->controller(ReturnController::class)->group(function () {
    Route::get('/', 'index')->name('return.index');
    Route::get('/create', 'create')->name('return.create');
    Route::post('/store', 'store')->name('return.store');
    Route::get('/detail', 'detail')->name('return.detail');
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
