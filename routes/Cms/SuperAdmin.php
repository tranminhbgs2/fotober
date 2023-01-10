<?php

Route::group(['prefix' => 'superadmin', 'middleware' => ['role:'.\App\Helpers\Constants::ACCOUNT_TYPE_SUPER_ADMIN]], function () {
    Route::get('/dashboard', 'Cms\DashboardController@home')->name('superadmin_dashboard');

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', 'Cms\SuperAdminController@listingCustomer')->name('superadmin_listing_customer');
        Route::post('/listing-ajax', 'Cms\SuperAdminController@listingAjaxCustomer')
            ->name('superadmin_listing_ajax_customer');
        Route::post('/show-info-ajax', 'Cms\SuperAdminController@showInfoAjaxCustomer')
            ->name('superadmin_show_info_ajax_customer');
        Route::post('/change-status-ajax', 'Cms\SuperAdminController@changeStatusAjaxCustomer')
            ->name('superadmin_change_status_ajax_customer');
    });
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', 'Cms\SuperAdminController@listingOrder')->name('superadmin_listing_order');
        Route::post('/listing-ajax', 'Cms\SuperAdminController@listingAjaxOrder')
            ->name('superadmin_listing_ajax_order');
        Route::post('/show-info-ajax', 'Cms\SuperAdminController@showInfoAjaxOrder')
            ->name('superadmin_show_info_ajax_order');
        Route::post('/requirement-listing', 'Cms\SuperAdminController@listingRequirementAjaxOrder')
            ->name('superadmin_requirement_listing_order');
        // Xem chi tiết + chat
        Route::get('/detail', 'Cms\SuperAdminController@detail')->name('superadmin_order_detail');
        // Form cập nhật
        Route::get('/edit', 'Cms\SuperAdminController@edit')->name('superadmin_order_edit');
        // Xử lý cập nhật
        Route::post('/update', 'Cms\SuperAdminController@update')->name('superadmin_order_update');
    });
    Route::group(['prefix' => 'reports'], function () {});
    // Các chức năng về nhóm người dùng.
    Route::group(['prefix' => 'groups'], function () {
        Route::get('/', 'Cms\SuperAdminController@listingGroup')->name('superadmin_listing_group');
        Route::post('/listing-ajax', 'Cms\SuperAdminController@listingAjaxGroup')
            ->name('superadmin_listing_ajax_group');
        //Route::get('/create', 'Cms\SuperAdminController@createGroup')->name('superadmin_create_group');
        //Route::post('/store', 'Cms\SuperAdminController@storeGroup')->name('superadmin_store_group');
        //Route::get('/edit', 'Cms\SuperAdminController@editGroup')->name('superadmin_edit_group');
        //Route::post('/update', 'Cms\SuperAdminController@updateGroup')->name('superadmin_update_group');
    });
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'Cms\SuperAdminController@listingUser')->name('superadmin_listing_user');
        Route::post('/listing-ajax', 'Cms\SuperAdminController@listingAjaxUser')
            ->name('superadmin_listing_ajax_user');

        Route::get('/create', 'Cms\SuperAdminController@createUser')->name('superadmin_create_user');
        Route::post('/store', 'Cms\SuperAdminController@storeUser')->name('superadmin_store_user');

        Route::get('/edit', 'Cms\SuperAdminController@editUser')->name('superadmin_edit_user');
        Route::post('/update', 'Cms\SuperAdminController@updateUser')->name('superadmin_update_user');

        Route::post('/change-status-ajax', 'Cms\SuperAdminController@changeStatusAjaxUser')
            ->name('superadmin_change_status_ajax_user');
    });
    Route::group(['prefix' => 'events'], function () {
        Route::get('/', 'Cms\SuperAdminController@listingEvent')->name('superadmin_listing_event');
        Route::post('/listing-ajax', 'Cms\SuperAdminController@listingAjaxEvent')
            ->name('superadmin_listing_ajax_event');

        Route::get('/create', 'Cms\SuperAdminController@createEvent')->name('superadmin_create_event');
        Route::post('/store', 'Cms\SuperAdminController@storeEvent')->name('superadmin_store_event');

        Route::get('/edit', 'Cms\SuperAdminController@editEvent')->name('superadmin_edit_event');
        Route::post('/update', 'Cms\SuperAdminController@updateEvent')->name('superadmin_update_event');

        Route::post('/change-status-ajax', 'Cms\SuperAdminController@changeStatusAjaxEvent')
            ->name('superadmin_change_status_ajax_event');
    });
    Route::group(['prefix' => 'services'], function () {
        Route::get('/', 'Cms\SuperAdminController@listingService')->name('superadmin_listing_service');
        Route::post('/listing-ajax', 'Cms\SuperAdminController@listingAjaxService')
            ->name('superadmin_listing_ajax_service');

        Route::get('/create', 'Cms\SuperAdminController@createService')->name('superadmin_create_service');
        Route::post('/store', 'Cms\SuperAdminController@storeService')->name('superadmin_store_service');

        Route::get('/edit', 'Cms\SuperAdminController@editService')->name('superadmin_edit_service');
        Route::post('/update', 'Cms\SuperAdminController@updateService')->name('superadmin_update_service');

        Route::post('/change-status-ajax', 'Cms\SuperAdminController@changeStatusAjaxService')
            ->name('superadmin_change_status_ajax_service');
    });
    Route::group(['prefix' => 'roles'], function () {});
    Route::group(['prefix' => 'permissions'], function () {});
    Route::group(['prefix' => 'resoures'], function () {
        Route::get('/', 'Cms\SuperAdminController@listingResoure')
            ->name('superadmin_listing_resoure');
    });
    Route::group(['prefix' => 'logs'], function () {
        Route::get('/in-out', 'Cms\SuperAdminController@listingInOutLog')
            ->name('superadmin_listing_in_out_log');
        Route::post('/in-out/listing-ajax', 'Cms\SuperAdminController@listingAjaxInOutLog')
            ->name('superadmin_listing_ajax_in_out_log');
    });

});
