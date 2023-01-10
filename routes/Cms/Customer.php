<?php

Route::group(['prefix' => 'customers'], function () {
    Route::get('/dashboard', 'Cms\DashboardController@home')->name('customer_dashboard');

    Route::group(['prefix' => 'orders'], function () {
        // Trang danh sách
        Route::get('/', 'Cms\CustomerController@order')->name('customer_order');

        // From thêm mới
        Route::get('/create', 'Cms\CustomerController@create')->name('customer_order_create');
        // Xử lý thêm mới
        Route::post('/store', 'Cms\CustomerController@store')->name('customer_order_store');

        // Xem chi tiết + chat
        Route::get('/detail', 'Cms\CustomerController@detail')->name('customer_order_detail');

        // Form cập nhật
        Route::get('/edit', 'Cms\CustomerController@edit')->name('customer_order_edit');

        // Xử lý cập nhật
        Route::post('/update', 'Cms\CustomerController@update')->name('customer_order_update');

        // Xóa order
        Route::get('/delete', 'Cms\CustomerController@delete')->name('customer_order_delete');

        Route::get('/send-request', 'Cms\CustomerController@sendRequest')->name('customer_order_send_request');

        // Tạo review
        Route::get('/preview', 'Cms\CustomerController@readyPreview')->name('customer_order_preview');
        Route::post('/accept-output', 'Cms\CustomerController@acceptOutput')->name('customer_order_accept_output');
        Route::post('/request-output', 'Cms\CustomerController@requestOutput')->name('customer_order_request_output');
        Route::post('/preview-submit', 'Cms\CustomerController@previewSubmit')->name('customer_order_preview_submit');
    });

    // Lấy ds order theo ajax
    Route::post('/order-listing-ajax', 'Cms\CustomerController@orderListingAjax')->name('customer_order_listing_ajax');

    // Xem nhanh thông tin qua ajax
    Route::post('/order-info-ajax', 'Cms\CustomerController@orderInfoAjax')->name('customer_order_info_ajax');

    // Lấy ds thanh toán
    Route::get('/payments', 'Cms\CustomerController@payment')->name('customer_payment');
    // Xác nhận thanh toán từ paypal
    Route::get('/payments/paypal', 'Cms\CustomerController@paypal')->name('customer_payment_paypal');
    // Lấy ds order theo ajax
    Route::post('/payment-listing-ajax', 'Cms\CustomerController@paymentListingAjax')->name('customer_payment_listing_ajax');
    // Xem nhanh thông tin qua ajax
    Route::post('/payment-info-ajax', 'Cms\CustomerController@paymentInfoAjax')->name('customer_payment_info_ajax');

    Route::get('/referal', 'Cms\CustomerController@referal')->name('customer_referal');
    Route::get('/report', 'Cms\CustomerController@report')->name('customer_report');
    Route::get('/setting', 'Cms\CustomerController@setting')->name('customer_setting');

    // Gửi tin nhắn
    Route::post('/send-message', 'Cms\CustomerController@sendMessage')->name('customer_send_message');

    // Gửi tin nhắn dạng ảnh
    Route::post('/send-message-file', 'Cms\CustomerController@sendMessageImage')->name('customer_send_message_file');

    // Xem nhanh input qua ajax
    Route::post('/order-input-ajax', 'Cms\CustomerController@orderInputAjax')->name('customer_order_input_ajax');
    // Xem nhanh input qua ajax
    Route::post('/order-output-ajax', 'Cms\CustomerController@orderOutputAjax')->name('customer_order_output_ajax');
    // Chat qua ajax
    Route::post('/order-chat-ajax', 'Cms\CustomerController@orderChatAjax')->name('customer_order_chat_ajax');

    //Down file zip
    Route::get('/zip/download', 'Cms\CustomerController@downZip')
        ->name('download_zip');

    Route::get('/zip/download-output', 'Cms\CustomerController@downOutputZip')
        ->name('download_output_zip');

    Route::group(['prefix' => 'services'], function () {
        Route::get('/', 'Cms\CustomerController@listingService')->name('customer_listing_service');
        Route::post('/listing-ajax', 'Cms\CustomerController@listingAjaxService')
            ->name('customer_listing_ajax_service');
    });

});
