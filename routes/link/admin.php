<?php

Route::group(['prefix'=>'admin','namespace'=>'admin','middleware'=> ['auth','admin']],function () {

    Route::get('dashboard', 'DashboardController@adminDashboard')->name('adminDashboard');
    Route::get('profile', 'DashboardController@adminProfile')->name('adminProfile');
    Route::post('user-profile-update', 'DashboardController@UserProfileUpdate')->name('UserProfileUpdate');
    Route::post('upload-profile-image', 'DashboardController@uploadProfileImage')->name('uploadProfileImage');
    Route::get('users', 'UserController@adminUsers')->name('adminUsers');
    Route::get('user-profile', 'UserController@adminUserProfile')->name('adminUserProfile');
    Route::get('user-add', 'UserController@UserAddEdit')->name('admin.UserAddEdit');
    Route::get('user-edit', 'UserController@UserEdit')->name('admin.UserEdit');
    Route::get('user-delete-{id}', 'UserController@adminUserDelete')->name('admin.user.delete');
    Route::get('user-suspend-{id}', 'UserController@adminUserSuspend')->name('admin.user.suspend');
    Route::get('user-active-{id}', 'UserController@adminUserActive')->name('admin.user.active');
    Route::get('user-remove-gauth-set-{id}', 'UserController@adminUserRemoveGauth')->name('admin.user.remove.gauth');
    Route::get('user-email-verify-{id}', 'UserController@adminUserEmailVerified')->name('admin.user.email.verify');
    Route::get('deleted-users', 'UserController@adminDeletedUser')->name('adminDeletedUser');
    Route::get('verification-details-{id}', 'UserController@VerificationDetails')->name('adminUserDetails');
 
    // ID Varification
    Route::get('pending-id-verified-user', 'UserController@adminUserIdVerificationPending')->name('adminUserIdVerificationPending');
    Route::get('verification-active-{id}-{type}', 'UserController@adminUserVerificationActive')->name('adminUserVerificationActive');
    Route::get('verification-reject', 'UserController@varificationReject')->name('varificationReject');

    Route::get('buy-coin-order', 'CoinController@adminPendingCoinOrder')->name('adminPendingCoinOrder');
    Route::get('approved-coin-order', 'CoinController@adminApprovedOrder')->name('adminApprovedOrder');
    Route::get('rejected-coin-order', 'CoinController@adminRejectedOrder')->name('adminRejectedOrder');
    Route::get('accept-pending-buy-coin-{id}', 'CoinController@adminAcceptPendingBuyCoin')->name('adminAcceptPendingBuyCoin');
    Route::get('reject-pending-buy-coin-{id}', 'CoinController@adminRejectPendingBuyCoin')->name('adminRejectPendingBuyCoin');
    Route::get('give-coin-history', 'CoinController@giveCoinHistory')->name('giveCoinHistory');
    Route::get('give-coin-to-user', 'CoinController@giveCoinToUser')->name('giveCoinToUser');
    Route::post('give-coin-to-user-process', 'CoinController@giveCoinToUserProcess')->name('giveCoinToUserProcess');

    Route::get('wallet-list', 'TransactionController@adminWalletList')->name('adminWalletList');
    Route::get('transaction-history', 'TransactionController@adminTransactionHistory')->name('adminTransactionHistory');

    Route::get('pending-withdrawal', 'TransactionController@adminPendingWithdrawal')->name('adminPendingWithdrawal');
    Route::get('rejected-withdrawal', 'TransactionController@adminRejectedWithdrawal')->name('adminRejectedWithdrawal');
    Route::get('active-withdrawal', 'TransactionController@adminActiveWithdrawal')->name('adminActiveWithdrawal');
    Route::get('accept-pending-withdrawal-{id}', 'TransactionController@adminAcceptPendingWithdrawal')->name('adminAcceptPendingWithdrawal');
    Route::get('reject-pending-withdrawal-{id}', 'TransactionController@adminRejectPendingWithdrawal')->name('adminRejectPendingWithdrawal');

    Route::get('banks', 'BankController@bankList')->name('bankList');
    Route::get('bank-add', 'BankController@bankAdd')->name('bankAdd');
    Route::get('bank-edit-{id}', 'BankController@bankEdit')->name('bankEdit');
    Route::get('bank-delete-{id}', 'BankController@bankDelete')->name('bankDelete');
    Route::post('bank-add-process', 'BankController@bankAddProcess')->name('bankAddProcess');

    Route::get('faq-list', 'SettingsController@adminFaqList')->name('adminFaqList');
    Route::get('faq-add', 'SettingsController@adminFaqAdd')->name('adminFaqAdd');
    Route::post('faq-save', 'SettingsController@adminFaqSave')->name('adminFaqSave');
    Route::get('faq-edit-{id}', 'SettingsController@adminFaqEdit')->name('adminFaqEdit');
    Route::get('faq-delete-{id}', 'SettingsController@adminFaqDelete')->name('adminFaqDelete');

    Route::get('general-settings', 'SettingsController@adminSettings')->name('adminSettings');
    Route::get('payment-methods', 'SettingsController@adminPaymentSetting')->name('adminPaymentSetting');
    Route::post('change-payment-methods', 'SettingsController@changePaymentMethodStatus')->name('changePaymentMethodStatus');
    Route::post('common-settings', 'SettingsController@adminCommonSettings')->name('adminCommonSettings');
    Route::post('save-payment-settings', 'SettingsController@adminSavePaymentSettings')->name('adminSavePaymentSettings');
    Route::post('email-save-settings', 'SettingsController@adminSaveEmailSettings')->name('adminSaveEmailSettings');
    Route::post('sms-save-settings', 'SettingsController@adminSaveSmsSettings')->name('adminSaveSmsSettings');
    Route::post('referral-fees-settings', 'SettingsController@adminReferralFeesSettings')->name('adminReferralFeesSettings');
    Route::post('withdrawal-settings', 'SettingsController@adminWithdrawalSettings')->name('adminWithdrawalSettings');
    Route::post('order-settings', 'SettingsController@adminOrderSettings')->name('adminOrderSettings');

    Route::get('membership-coin-transaction-history', 'ClubController@coinTransactionHistory')->name('coinTransactionHistory');
    Route::get('membership-list', 'ClubController@membershipList')->name('membershipList');
    Route::get('plan-list', 'ClubController@planList')->name('planList');
    Route::get('plan-edit-{id}', 'ClubController@planEdit')->name('planEdit');
    Route::post('plan-save', 'ClubController@planSave')->name('planSave');
    Route::get('club-bonus-distribution', 'ClubController@clubBonusDistribution')->name('clubBonusDistribution');
    Route::get('admin-club-bonus-distribution-process', 'ClubController@adminClubBonusDistribution')->name('adminClubBonusDistribution');

    Route::get('send-email', 'DashboardController@sendEmail')->name('sendEmail');
    Route::get('clear-email', 'DashboardController@clearEmailRecord')->name('clearEmailRecord');
    Route::get('send-notification', 'DashboardController@sendNotification')->name('sendNotification');
    Route::post('send-notification-process', 'DashboardController@sendNotificationProcess')->name('sendNotificationProcess');
    Route::post('send-email-process', 'DashboardController@sendEmailProcess')->name('sendEmailProcess');

});
