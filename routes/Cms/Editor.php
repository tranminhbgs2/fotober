<?php

Route::group(['prefix' => 'editor'], function () {
    Route::get('/dashboard', 'Cms\DashboardController@home')->name('editor_dashboard');

    // Trang danh sách
    Route::get('/orders', 'Cms\EditorController@order')->name('editor_order');

    // Lấy ds order theo ajax
    Route::post('/order-listing-ajax', 'Cms\EditorController@orderListingAjax')->name('editor_order_listing_ajax');

    // Xem nhanh thông tin qua ajax
    Route::post('/order-info-ajax', 'Cms\EditorController@orderInfoAjax')->name('editor_order_info_ajax');
    
    // Xử lý item yêu cầu bao gồm show list và add/delete item
    Route::post('/order-requirement-list', 'Cms\EditorController@listingRequirement')
        ->name('editor_order_requirement_list');

    // Đổi status order
    Route::post('/order-change-status', 'Cms\EditorController@changeStatus')
        ->name('editor_order_change_status_ajax');
    
    // Đổi status requirement
    Route::post('/requirement-change-status', 'Cms\EditorController@changeStatusRequirement')
        ->name('editor_requirement_change_status_ajax');
        
    //Down file zip
    Route::get('/zip/download', 'Cms\EditorController@downZip')
    ->name('download_zip');
});
