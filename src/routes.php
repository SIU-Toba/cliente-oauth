<?php


Route::group(array('prefix' => 'oauth'), function () {

    Route::get('/access_token', array(
        'as' => 'oauth_access_token',
        'uses' => 'SIUToba\ClienteOauth\Controllers\ClienteOauthController@getAccessToken',
    ));

});
