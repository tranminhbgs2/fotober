<?php

Route::group(['prefix' => 'sales'], function () {
    Route::get('/dashboard', 'Cms\DashboardController@home')->name('sale_dashboard');

    // Trang danh sách
    Route::get('/orders', 'Cms\SaleController@order')->name('sale_order');

    // From thêm mới
    Route::get('/orders/create', 'Cms\SaleController@create')->name('sale_order_create');
    // Xử lý thêm mới
    Route::post('/orders/store', 'Cms\SaleController@store')->name('sale_order_store');

    // Xem chi tiết + chat
    Route::get('/orders/detail', 'Cms\SaleController@detail')->name('sale_order_detail');

    // Form cập nhật
    Route::get('/orders/edit', 'Cms\SaleController@edit')->name('sale_order_edit');
    // Xử lý cập nhật
    Route::post('/orders/update', 'Cms\SaleController@update')->name('sale_order_update');

    // Xóa order
    Route::get('/orders/delete', 'Cms\SaleController@delete')->name('sale_order_delete');

    // Lấy ds order theo ajax
    Route::post('/order-listing-ajax', 'Cms\SaleController@orderListingAjax')->name('sale_order_listing_ajax');

    // Xem nhanh thông tin qua ajax
    Route::post('/order-info-ajax', 'Cms\SaleController@orderInfoAjax')->name('sale_order_info_ajax');

    // Sale admin cập nhật giao việc cho Sale member
    Route::post('/order-update-assign-sale-ajax', 'Cms\SaleController@updateAssignSale')
        ->name('sale_order_update_assign_sale_ajax');

    // Sale tạo thanh toán, sau đó để thêm chi tiết thanh toán vào
    Route::get('/order-init-payment', 'Cms\SaleController@orderInitPayment')
        ->name('sale_order_init_payment');

    // Xử lý item yêu cầu bao gồm show list và add/delete item
    Route::post('/order-requirement-list', 'Cms\SaleController@listingRequirement')
        ->name('sale_order_requirement_list');
    // Thêm item
    Route::post('/order-requirement-add', 'Cms\SaleController@storeRequirement')
        ->name('sale_order_requirement_add');
    // Xóa item
    Route::post('/order-requirement-delete', 'Cms\SaleController@deleteRequirement')
        ->name('sale_order_requirement_delete');

    // Xử lý item thanh toán bao gồm show list và add/delete item
    Route::post('/order-show-invoice-detail', 'Cms\SaleController@showInvoiceDetail')
        ->name('sale_order_show_invoice_detail');
    // Thêm item
    Route::post('/order-update-payment-detail', 'Cms\SaleController@updatePaymentDetail')
        ->name('sale_order_update_payment_detail');
    // Xóa item
    Route::post('/order-delete-payment-detail', 'Cms\SaleController@deletePaymentDetail')
        ->name('sale_order_delete_payment_detail');

    // Gửi tin nhắn
    Route::post('/send-message', 'Cms\SaleController@sendMessage')->name('sale_send_message');
    // Gửi tin nhắn dạng ảnh và file
    Route::post('/send-message-file', 'Cms\SaleController@sendMessageImage')->name('customer_send_message_file');

    // Lấy ds thanh toán
    Route::get('/payments', 'Cms\SaleController@payment')->name('sale_payment');
    // Xác nhận thanh toán từ paypal
    Route::get('/payments/paypal', 'Cms\SaleController@paypal')->name('sale_payment_paypal');
    // Lấy ds order theo ajax
    Route::post('/payment-listing-ajax', 'Cms\SaleController@paymentListingAjax')
        ->name('sale_payment_listing_ajax');
    // Xem nhanh thông tin qua ajax
    Route::post('/payment-info-ajax', 'Cms\SaleController@paymentInfoAjax')
        ->name('sale_payment_info_ajax');

    Route::get('/payments/create-and-send-invoice-to-paypal', 'Cms\SaleController@createAndSendInvoiceToPaypal')
        ->name('sale_create_and_send_invoice_to_paypal');

    // Nhóm chức năng quản lý KH của Sale
    Route::get('/customers', 'Cms\SaleController@listingCustomer')
        ->name('sale_customer_listing');
    //Hiển thị danh sách user
    Route::post('/customer-list-ajax', 'Cms\SaleController@saleListCustomerAjax')
        ->name('sale_customer_listing_ajax');
    // Hiển thị thông tin user
    Route::post('/customer-info-ajax', 'Cms\SaleController@saleInfoCustomerAjax')
        ->name('sale_customer_info_ajax');
    // Hiển thị form thêm mới
    Route::get('/customers/create', 'Cms\SaleController@createCustomer')
        ->name('sale_customer_create');
    //Tạo mới customer
    Route::post('/customers/store', 'Cms\SaleController@storeCustomer')
        ->name('sale_customer_store');
    //Hiển thị form edit
    Route::get('/customers/edit', 'Cms\SaleController@editCustomer')
        ->name('sale_customer_edit');
    // Chỉnh sửa customer
    Route::post('/customers/update', 'Cms\SaleController@updateCustomer')
        ->name('sale_customer_update');
    // Gán quyền sale quản lý customer
    Route::post('/customers/assign_sale', 'Cms\SaleController@updateAssignSaleCustomer')
        ->name('sale_customer_update_assign_sale_ajax');
    // Xóa customer
    Route::get('/customers/delete', 'Cms\SaleController@deleteCustomer')
        ->name('sale_customer_delete');

    // Xử lý item output bao gồm show list và add/delete item
    Route::post('/order-output-list', 'Cms\SaleController@listingOutput')
    ->name('sale_order_output_list');
    Route::post('/order-output-list-sumary', 'Cms\SaleController@listingOutputSumary')
    ->name('sale_order_output_list_sumary');
    // Thêm item
    Route::post('/order-output-add', 'Cms\SaleController@storeOutput')
        ->name('sale_order_output_add');
    // Cập nh item
    Route::post('/order-output-update', 'Cms\SaleController@updateOutput')
            ->name('sale_order_output_update');
    // Xóa item
    Route::post('/order-output-delete', 'Cms\SaleController@deleteOutput')
        ->name('sale_order_output_delete');

    Route::get('/orders/forward-to-admin', 'Cms\SaleController@forwardSaleToAdmin')
        ->name('sale_order_forward_to_admin');

    // Trang danh sách KPI
    Route::get('/kpis', 'Cms\SaleController@kpi')->name('sale_kpi');
    //Hiển thị danh sách KPI
    Route::post('/kpi-list-ajax', 'Cms\SaleController@saleListKpiAjax')
        ->name('sale_kpi_listing_ajax');
    //Cập nhật trạng thái đơn hàng
    Route::post('/chang-status-ajax', 'Cms\SaleController@changeStatus')
        ->name('sale_change_status_ajax');

    // Xem nhanh thông tin qua ajax
    Route::post('/order-input-ajax', 'Cms\SaleController@orderInputAjax')->name('sale_order_input_ajax');
    // Xem nhanh thông tin qua ajax
    Route::post('/order-output-list-ajax', 'Cms\SaleController@orderOutputAjax')->name('sale_order_output_list_ajax');

    //Down file zip
    Route::get('/zip/download', 'Cms\SaleController@downZip')
        ->name('download_zip');

    Route::group(['prefix' => 'reports'], function () {
        // Thống kê lượng order theo từng trạng thái của từng sale
        Route::get('/', 'Cms\SaleController@listingReport')->name('sale_listing_report');
        Route::post('/listing-report-ajax', 'Cms\SaleController@listingReportAjax')->name('sale_listing_report_ajax');
    });
    // Chat qua ajax
    Route::post('/order-chat-ajax', 'Cms\SaleController@orderChatAjax')->name('sale_order_chat_ajax');
    Route::get('/order-summary', 'Cms\SaleController@readySummary')->name('sale_order_summary');
    Route::post('/order-summary-create-invoice', 'Cms\SaleController@CreateInvoicePaypal')->name('sale_order_create_invoice');
    Route::get('/order-summary-edit', 'Cms\SaleController@readySummaryUpdate')->name('sale_order_summary_update');
    Route::post('/order-summary-edit-invoice', 'Cms\SaleController@EditInvoicePaypal')->name('sale_order_edit_invoice');
});
