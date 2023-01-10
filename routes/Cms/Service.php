<?php

/**
 * /services/
 * /services/listing-ajax
 */
Route::group(['prefix' => 'services'], function () {
    Route::get('/', 'Cms\ServiceController@listing')->name('service_listing');
    Route::post('/listing-ajax', 'Cms\ServiceController@listingAjax')->name('service_listing_ajax');

});
