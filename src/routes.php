<?php

use Illuminate\Support\Facades\Route;
use ACTCMS\Utility\Controller\UtilityController;
use ACTCMS\Utility\VugiChugi;

Route::middleware(VugiChugi::gtc())->controller(UtilityController::class)->group(function(){
    Route::get(VugiChugi::acRouter(),'laraminStart')->name(VugiChugi::acRouter());
    Route::post(VugiChugi::acRouterSbm(),'laraminSubmit')->name(VugiChugi::acRouterSbm());
});
