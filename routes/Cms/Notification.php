<?php

/**
 * /notifications/draft-order-ajax
 */
Route::group(['prefix' => 'notifications'], function () {
    //
    Route::get('/', 'Cms\NotificationController@index')->name('notifications');
    Route::post('/listing-ajax', 'Cms\NotificationController@listingAjax')->name('notifications_listing_ajax');
    Route::post('/by-user-ajax', 'Cms\NotificationController@listingByUserAjax')->name('listing_by_user_ajax');
});
