<?php

/**
 * /dashboard/draft-order-ajax
 */
Route::group(['prefix' => 'dashboard'], function () {
    //
    Route::post('/summary-order', 'Cms\DashboardController@summaryOrderAjax')->name('dashboard_summary_order_ajax');
    Route::post('/draft-order-ajax', 'Cms\DashboardController@draftOrderAjax')->name('dashboard_draft_order_ajax');
    Route::post('/new-order-ajax', 'Cms\DashboardController@newOrderAjax')->name('dashboard_new_order_ajax');
    Route::post('/deadline-order-ajax', 'Cms\DashboardController@deadlineOrderAjax')->name('dashboard_deadline_order_ajax');
    Route::post('/recent-order-ajax', 'Cms\DashboardController@recentOrderAjax')->name('dashboard_recent_order_ajax');
    Route::post('/edited-order-ajax', 'Cms\DashboardController@editedOrderAjax')->name('dashboard_edited_order_ajax');
    Route::post('/re-do-order-ajax', 'Cms\DashboardController@redoOrderAjax')->name('dashboard_redo_order_ajax');
});
