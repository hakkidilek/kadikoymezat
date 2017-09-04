<?php

use App\UsersModel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'FrontEndController@index');
Route::get('/about', 'FrontEndController@about');
Route::get('/faq', 'FrontEndController@faq');
Route::get('/contact', 'FrontEndController@contact');
Route::get('/services/{category}', 'FrontEndController@category');
Route::get('/auction/{id}/bid', 'FrontEndController@bid');
Route::get('/auction/{id}/buy', 'FrontEndController@buynow');
Route::post('/subscribe', 'FrontEndController@subscribe');
Route::post('/profile/email', 'FrontEndController@usermail');
Route::post('/contact/email', 'FrontEndController@contactmail');
Route::get('/auction/{id}', 'FrontEndController@viewauction');
Route::get('/featured', 'FrontEndController@featured');
Route::get('/category/{category}', 'FrontEndController@category');
Route::get('/stripe', 'FrontEndController@stripe');


Route::get('/admin', function () {
    return view('admin.index');
})->name('admin.login');


Route::get('/login', function () {
    return view('admin.login');
});

Auth::routes();

Route::get('/admin/dashboard', 'HomeController@index');

Route::post('admin/settings/title', 'SettingsController@title');
Route::post('admin/settings/favicon', 'SettingsController@favicon');
Route::post('admin/settings/payment', 'SettingsController@payment');
Route::post('admin/settings/about', 'SettingsController@about');
Route::post('admin/settings/address', 'SettingsController@address');
Route::post('admin/settings/footer', 'SettingsController@footer');
Route::post('admin/settings/logo', 'SettingsController@logo');
Route::post('admin/settings/background', 'SettingsController@background');
Route::resource('/admin/settings', 'SettingsController');

Route::resource('/admin/sliders', 'SliderController');

Route::post('/admin/category/titles', 'CategoryController@titles');
Route::resource('/admin/category', 'CategoryController');

Route::post('/admin/service/titles', 'ServiceSectionController@titles');
Route::resource('/admin/service', 'ServiceSectionController');

Route::post('/admin/testimonial/titles', 'TestimonialController@titles');
Route::resource('/admin/testimonial', 'TestimonialController');

Route::post('/admin/package/titles', 'PackageController@titles');
Route::resource('/admin/package', 'PackageController');


Route::get('/admin/auction/pending', 'AuctionController@pending');
Route::get('/admin/auction/{id}/pending', 'AuctionController@pendingview');
Route::get('/admin/auction/{id}/accept', 'AuctionController@accept');
Route::get('/admin/auction/{id}/reject', 'AuctionController@reject');
Route::get('/admin/auction/{id}/hardreject', 'AuctionController@hardreject');

Route::post('/admin/auction/{bid}/sendemail', 'UserAuctionController@sendemail');
Route::get('/admin/auction/{bid}/email', 'UserAuctionController@emailbidder');
Route::post('/admin/auction/titles', 'AuctionController@titles');
Route::get('/admin/auction/{id}/delete', 'AuctionController@delete');
Route::get('/admin/auction/{id}/open', 'AuctionController@open');
Route::get('/admin/auction/{id}/close', 'AuctionController@close');
Route::get('/admin/auction/{bid}/winner/{auction}', 'AuctionController@makeWinner');
Route::get('/admin/auction/{auction}/cwinner', 'AuctionController@cancelWinner');
Route::get('/admin/auction/{id}/withdraw', 'AuctionController@withdraw');
Route::post('/admin/auction/{id}/withdraw', 'AuctionController@withdraws');
Route::resource('/admin/auction', 'AuctionController');

Route::resource('/admin/counter', 'CounterController');
Route::post('/admin/portfolio/titles', 'PortfolioController@titles');
Route::resource('/admin/portfolio', 'PortfolioController');

Route::resource('/admin/services', 'ServiceController');
Route::resource('/admin/category', 'CategoryController');

Route::post('admin/pagesettings/about', 'PageSettingsController@about');
Route::post('admin/pagesettings/faq', 'PageSettingsController@faq');
Route::post('admin/pagesettings/contact', 'PageSettingsController@contact');
Route::resource('/admin/pagesettings', 'PageSettingsController');

Route::get('admin/ads/status/{id}/{status}', 'AdvertiseController@status');

Route::resource('/admin/ads', 'AdvertiseController');
Route::resource('/admin/social', 'SocialLinkController');
Route::resource('/admin/tools', 'SeoToolsController');
Route::get('admin/subscribers/download', 'SubscriberController@download');

Route::resource('/admin/subscribers', 'SubscriberController');
Route::post('/admin/adminpassword/change/{id}', 'AdminProfileController@changepass');
Route::get('/admin/adminpassword', 'AdminProfileController@password');
Route::resource('/admin/adminprofile', 'AdminProfileController');

Route::get('/admin/withdraws/pending', 'AdminWithdrawController@pending');
Route::get('/admin/withdraws/accept/{id}', 'AdminWithdrawController@accept');
Route::get('/admin/withdraws/reject/{id}', 'AdminWithdrawController@reject');
Route::resource('/admin/withdraws', 'AdminWithdrawController');

Route::get('/admin/users/email/{id}', 'UsersController@email');
Route::get('/admin/users/status/{id}/{status}', 'UsersController@status');
Route::post('/admin/users/emailsend', 'UsersController@sendemail');
Route::resource('/admin/users', 'UsersController');

Route::post('/payment', 'PaymentController@store')->name('payment.submit');
Route::get('/payment/cancle', 'PaymentController@paycancle')->name('payment.cancle');
Route::get('/payment/return', 'PaymentController@payreturn')->name('payment.return');
Route::post('/payment/notify', 'PaymentController@notify')->name('payment.notify');

Route::post('/stripe-submit', 'StripeController@store')->name('stripe.submit');

Route::post('/paypal-publish', 'PublishPaypalController@store')->name('paypal.publish');
Route::get('/paypal/cancle', 'PublishPaypalController@paycancle')->name('paypal.cancle');
Route::get('/paypal/return', 'PublishPaypalController@payreturn')->name('paypal.return');
Route::post('/paypal/notify', 'PublishPaypalController@notify')->name('paypal.notify');

Route::post('/stripe-publish', 'PublishStripeController@store')->name('stripe.publish');

Route::post('/winner-pay', 'PayPaypalController@store')->name('winner.pay');
Route::get('/winner/cancle', 'PayPaypalController@paycancle')->name('winner.cancle');
Route::get('/winner/return', 'PayPaypalController@payreturn')->name('winner.return');
Route::post('/winner/notify', 'PayPaypalController@notify')->name('winner.notify');

Route::post('/stripe-winner', 'PayStripeController@store')->name('stripe.winner');

Route::get('user/dashboard', 'UserProfileController@index')->name('user.dashboard');

Route::post('/user/password/change/{id}', 'UserProfileController@changepass');
Route::get('/user/password', 'UserProfileController@password');
Route::get('/user/profile', 'UserProfileController@profile');
Route::post('/user/update/{id}', 'UserProfileController@update');

Route::post('/user/auction/{bid}/sendemail', 'UserAuctionController@sendemail');
Route::get('/user/winner/{bid}/pay', 'UserBidController@winnerpay');
Route::get('/user/auction/{bid}/email', 'UserAuctionController@emailbidder');
Route::get('/user/auction/{id}/delete', 'UserAuctionController@delete');
Route::get('/user/auction/{id}/open', 'UserAuctionController@open');
Route::get('/user/auction/{id}/close', 'UserAuctionController@close');
Route::get('/user/auction/{bid}/winner/{auction}', 'UserAuctionController@makeWinner');
Route::get('/user/auction/{auction}/cwinner', 'UserAuctionController@cancelWinner');
Route::get('/user/withdraw', 'UserAuctionController@withdrawlist');
Route::get('/user/withdrawform', 'UserAuctionController@withdraw');
Route::post('/user/withdraw/submit', 'UserAuctionController@withdraws');
Route::get('/user/auction/{id}/publish', 'UserAuctionController@publish');
Route::post('/user/auction/publishnow', 'UserAuctionController@publishnow')->name('user.auction.publish');
Route::resource('user/auction', 'UserAuctionController');

Route::resource('user/mybids', 'UserBidController');

Route::get('/user/login', 'Auth\ProfileLoginController@showLoginFrom')->name('user.login');
Route::post('/user/login', 'Auth\ProfileLoginController@login')->name('user.login.submit');
Route::get('/user/registration', 'Auth\ProfileRegistrationController@showRegistrationForm')->name('user.reg');
Route::post('/user/registration', 'Auth\ProfileRegistrationController@register')->name('user.reg.submit');

Route::get('/user/forgot', 'Auth\ProfileResetPassController@showForgotForm')->name('user.forgotpass');
Route::post('/user/forgot', 'Auth\ProfileResetPassController@resetPass')->name('user.forgotpass.submit');

