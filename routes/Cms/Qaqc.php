<?php

Route::group(['prefix' => 'qaqc'], function () {
    Route::get('/dashboard', 'Cms\DashboardController@home')->name('qaqc_dashboard');

    // Trang danh sách
    Route::get('/orders', 'Cms\QaqcController@order')->name('qaqc_order');

    // Lấy ds order theo ajax
    Route::post('/order-listing-ajax', 'Cms\QaqcController@orderListingAjax')->name('qaqc_order_listing_ajax');

    // Xem nhanh thông tin qua ajax
    Route::post('/order-info-ajax', 'Cms\QaqcController@orderInfoAjax')->name('qaqc_order_info_ajax');

    // Xử lý item yêu cầu bao gồm show list và add/delete item
    Route::post('/order-requirement-list', 'Cms\QaqcController@listingRequirement')
        ->name('qaqc_order_requirement_list');

    // Đổi status order
    Route::post('/order-change-status', 'Cms\QaqcController@changeStatus')
        ->name('qaqc_order_change_status_ajax');
    // Đổi status requirement
    Route::post('/requirement-change-status', 'Cms\QaqcController@changeStatusRequirement')
        ->name('qaqc_requirement_change_status_ajax');
        
    // Đổi status requirement
    Route::post('/requirement-change-status', 'Cms\QaqcController@changeStatusRequirement')
        ->name('qaqc_requirement_change_status_ajax');
    // Thêm item
    Route::post('/order-requirement-add', 'Cms\QaqcController@storeRequirement')
        ->name('qaqc_order_requirement_add');
        
    //Down file zip
    Route::get('/zip/download', 'Cms\QaqcController@downZip')
    ->name('download_zip');
});
