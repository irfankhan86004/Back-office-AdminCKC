<?php

// Logout
Route::get('logout', 'AdminAuth\AuthController@logout')->name('admin_logout');

// Dashboard
Route::get('dashboard', 'Admin\DashboardController@index')->name('admin_dashboard');
Route::post('dashboard/availables-rooms', 'Admin\DashboardController@availablesRooms')->name('admin_avalaibles_rooms');

// Admins
Route::resource('admins', 'Admin\AdminController');
Route::any('/remove_avatar', 'Admin\AdminController@remove_avatar')->name('remove_avatar');

// Configuration
Route::resource('settings', 'Admin\SettingController', ['only' => ['index', 'update']]);

// Tags
Route::resource('tags', 'Admin\TagController');
Route::any('/tags-ajaxlisting', 'Admin\TagController@ajax_listing')->name('tags_ajaxlisting');

// Promo codes
Route::resource('promo-codes', 'Admin\PromoCodeController');
Route::get('/promo-codes-ajaxlisting', 'Admin\PromoCodeController@ajax_listing')->name('promo_codes_ajaxlisting');

// Catégories du blog
Route::resource('categories-blog', 'Admin\BlogCategoryController');
Route::any('/categories-blog-ajaxlisting', 'Admin\BlogCategoryController@ajax_listing')->name('categories_blog_ajaxlisting');
Route::get('/categories-blog-order', 'Admin\BlogCategoryController@getorder')->name('admin_categories_blog_order');
Route::any('/set-order-categories-blog', 'Admin\BlogCategoryController@set_order_categories_blog')->name('set_order_categories_blog');

// Articles du blog
Route::resource('articles-blog', 'Admin\BlogPostController');
Route::any('/articles-blog-ajaxlisting', 'Admin\BlogPostController@ajax_listing')->name('articles_blog_ajaxlisting');
Route::any('/articles-blog-getevent', 'Admin\BlogPostController@getEventFile')->name('articles_blog.get_outlook_event');

// Menu
Route::resource('menu', 'Admin\MenuController');
Route::get('menu-order', 'Admin\MenuController@getorder')->name('admin.menu.order');
Route::any('/set-order-menus', 'Admin\MenuController@setordermenus')->name('setordermenus');

// Pages
Route::resource('pages', 'Admin\PageController');
Route::any('/pages-ajaxlisting', 'Admin\PageController@ajax_listing')->name('pages_ajaxlisting');
Route::get('pages-order', 'Admin\PageController@getorder')->name('admin.pages.order');
Route::any('/pages-order-ajaxlisting', 'Admin\PageController@order_ajax_listing')->name('pages_order_ajaxlisting');
Route::any('/set-order-pages', 'Admin\PageController@setorderpages')->name('setorderpages');
Route::get('/pages-edition/{page}/{language}', 'Admin\PageController@edition')->name('page_edition');
Route::post('/pages-save-edition', 'Admin\PageController@save_edition')->name('save_page_edition');
Route::get('/pages-duplicate/{page}', 'Admin\PageController@duplicate')->name('page_duplicate');
Route::post('/pages-create-redirect/{page}', 'Admin\PageController@create_redirect')->name('page_create_redirect');
Route::get('/pages-history-ajaxlisting', 'Admin\PageController@history_ajax_listing')->name('page_history_ajaxlisting');
Route::post('/pages-restore-history', 'Admin\PageController@restore_history')->name('page_restore_history');
Route::post('/pages-preview/{page}', 'Admin\PageController@pagePreview')->name('page_preview');
Route::get('/pages-replace/{page}/{language}/{sourceLang}', 'Admin\PageController@replaceLang')->name('page_replace');

// Carousel
Route::resource('carousel', 'Admin\CarouselController');
Route::get('carousel/order/{language_id}', 'Admin\CarouselController@getorder')->name('admin.carousel.order');
Route::any('/setordercarousel', 'Admin\CarouselController@setordercarousel')->name('setordercarousel');

// Catégories de médias
Route::resource('categories-medias', 'Admin\MediaCategoryController');
Route::post('/categories-medias-order', 'Admin\MediaCategoryController@order')->name('categories_medias_order');

// Medias
Route::resource('medias', 'Admin\MediaController', ['except' => ['create', 'store']]);
Route::post('/upload-media', 'Admin\MediaController@upload')->name('upload_media');
Route::get('/medias-ajaxlisting', 'Admin\MediaController@ajax_listing')->name('medias_ajaxlisting');
Route::get('/medias-ajaxlisting-partial', 'Admin\MediaController@ajax_listing_partial')->name('medias_ajaxlisting_partial');
Route::post('/medias-set-order', 'Admin\MediaController@set_order')->name('medias_set_order');
Route::post('/remove-media', 'Admin\MediaController@remove')->name('remove_media');
Route::post('/media-affectation', 'Admin\MediaController@affectation')->name('media_affectation');
Route::get('/medias-autocomplete', 'Admin\MediaController@autocomplete')->name('medias_autocomplete');
Route::post('/medias-multiple-actions', 'Admin\MediaController@mulitple_actions')->name('medias_multiple_actions');
Route::post('/medias-merge', 'Admin\MediaController@merge')->name('medias_merge');
Route::get('/medias-export', 'Admin\MediaController@export')->name('medias_export');
Route::get('/find-missing-medias', 'Admin\MediaController@find_missing_medias')->name('find_missing_medias');

// Emails
Route::resource('emails', 'Admin\LogEmailController', ['except' => ['create', 'store']]);
Route::any('/emails-ajaxlisting', 'Admin\LogEmailController@ajax_listing')->name('emails_ajaxlisting');

// SMS
Route::resource('sms', 'Admin\LogSmsController', ['except' => ['create', 'store']]);
Route::any('/sms-ajaxlisting', 'Admin\LogSmsController@ajax_listing')->name('sms_ajaxlisting');

// Contact requests
Route::resource('contact-requests', 'Admin\ContactRequestController', ['except' => ['create', 'store', 'show']]);
Route::any('/contact-requests-ajaxlisting', 'Admin\ContactRequestController@ajax_listing')->name('contact_requests_ajaxlisting');

// Utilisateurs
Route::get('/users/{user}/messages', 'Admin\UserMessagesController@index')->name('users.messages.index');
Route::post('/users/{user}/messages/create', 'Admin\UserMessagesController@create')->name('users.messages.create');
Route::get('/users/messages/{userMessage}/delete', 'Admin\UserMessagesController@delete')->name('users.messages.delete');
Route::get('/users/messages/attachments/{userMessageAttachment}/delete', 'Admin\UserMessagesController@deleteAttachment')->name('users.messages.deleteAttachment');
Route::post('/users/messages/edit', 'Admin\UserMessagesController@edit')->name('users.messages.edit');
Route::get('/users/{user}/messages/more', 'Admin\UserMessagesController@moreMessages')->name('users.messages.more');
Route::resource('users', 'Admin\UserController');
Route::any('/users-ajaxlisting', 'Admin\UserController@ajax_listing')->name('users_ajaxlisting');

// Newsletters
Route::resource('newsletters', 'Admin\NewslettersController');
Route::get('/newsletters-ajaxlisting', 'Admin\NewslettersController@ajax_listing')->name('newsletters_ajaxlisting');
Route::get('/newsletters-export', 'Admin\NewslettersController@export')->name('newsletters.export');

// Redirections
Route::resource('redirects', 'Admin\RedirectController');
Route::get('/redirects-ajaxlisting', 'Admin\RedirectController@ajax_listing')->name('redirects_ajaxlisting');
Route::get('/redirects-export', 'Admin\RedirectController@export')->name('redirects_export');
