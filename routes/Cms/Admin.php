<?php

Route::group(['prefix' => 'admin'], function () {
    Route::get('/dashboard', 'Cms\DashboardController@home')->name('admin_dashboard');

    // Trang danh sách
    Route::get('/orders', 'Cms\AdminController@order')->name('admin_order');

    // Lấy ds order theo ajax
    Route::post('/order-listing-ajax', 'Cms\AdminController@orderListingAjax')->name('admin_order_listing_ajax');

    // Xem nhanh thông tin qua ajax
    Route::post('/order-info-ajax', 'Cms\AdminController@orderInfoAjax')->name('admin_order_info_ajax');

    // Gán order cho editer qua ajax
    Route::post('/order-assign-editor-ajax', 'Cms\AdminController@orderAssignEditorAjax')->name('admin_order_assign_editor_ajax');

    // Xử lý item yêu cầu bao gồm show list và add/delete item
    Route::post('/order-requirement-list', 'Cms\AdminController@listingRequirement')
        ->name('admin_order_requirement_list');
    // Thêm item
    Route::post('/order-requirement-add', 'Cms\AdminController@storeRequirement')
        ->name('admin_order_requirement_add');
    // Xóa item
    Route::post('/order-requirement-delete', 'Cms\AdminController@deleteRequirement')
        ->name('admin_order_requirement_delete');
    // Đổi status order
    Route::post('/order-change-status', 'Cms\AdminController@changeStatus')
        ->name('admin_order_change_status_ajax');

        
    //Down file zip
    Route::get('/zip/download', 'Cms\AdminController@downZip')
    ->name('download_zip');
});
