<?php

Route::group(['prefix' => 'accounts'], function () {
    // Trang thông tin tài khoản
    Route::get('/profile', 'Cms\AccountController@showProfile')->name('account_show_profile');
    Route::get('/change-password', 'Cms\AccountController@showChangePassword')->name('account_change_password');
    Route::post('/change-password', 'Cms\AccountController@processChangePassword')->name('account_process_change_password');
    Route::get('/update-profile', 'Cms\AccountController@editProfile')->name('account_edit_profile');
    Route::post('/update-profile', 'Cms\AccountController@updateProfile')->name('account_update_profile');


});
