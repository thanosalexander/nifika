<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
/* @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->defineAs(App\User::class, 'AdminUser', function (Faker\Generator $faker) {
    $pass = env('APP_ADMIN_USER_PASS');
    return [
        'type' => App\User::TYPE_ADMIN,
        'name' => 'OOB Admin',
        'email' => '',
        'username' => 'oobAdmin',
        'password' => bcrypt(!empty($pass) ? $pass : 'secret1'),
        'enabled' => App\User::ENABLED_YES,
    ];
});

$factory->defineAs(App\User::class, 'ManagerUser', function (Faker\Generator $faker) {
    $pass = env('APP_MANAGER_USER_PASS');
    return [
        'type' => App\User::TYPE_USER,
        'name' => 'Manager',
        'email' => '',
        'username' => 'admin',
        'password' => bcrypt(!empty($pass) ? $pass : 'secret2'),
        'enabled' => App\User::ENABLED_YES,
    ];
});

$factory->define(App\Page::class, function (Faker\Generator $faker) {
    $title = 'Page' . $faker->words(1, true);
    return [
        'type' => App\Page::TYPE_PAGE,
        'title' => $title,
        'slug' => \Illuminate\Support\Str::slug($title),
        'description' => $faker->sentences(1, true),
        'content' => $faker->sentences(4, true),
        'enabled' => App\Page::ENABLED_YES,
    ];
});
$factory->defineAs(App\Page::class, 'Article', function (Faker\Generator $faker) {
    $title = 'Article' . $faker->words(3, true);
    return [
        'type' => App\Page::TYPE_ARTICLE,
        'title' => $title,
        'slug' => \Illuminate\Support\Str::slug($title),
        'description' => $faker->sentences(1, true),
        'content' => $faker->sentences(4, true),
        'enabled' => App\Page::ENABLED_YES,
    ];
});
$factory->defineAs(App\Language::class, 'FrontendLanguage', function (Faker\Generator $faker) {
    return [
        'type' => App\Language::TYPE_FRONTEND,
        'code' => config('app.locale'),
        'sort' => 0,
        'enabled' => App\Language::ENABLED_YES,
    ];
});
$factory->defineAs(App\Language::class, 'BackendLanguage', function (Faker\Generator $faker) {
    return [
        'type' => App\Language::TYPE_BACKEND,
        'code' => config('app.locale'),
        'sort' => 0,
        'enabled' => App\Language::ENABLED_YES,
    ];
});
$factory->define(App\MenuItem::class, function (Faker\Generator $faker) {
    return [
        'menu_id' => App\Logic\Template\Menu\Menus::MENU_ID_MAIN,
        'type' => \App\MenuItem::TYPE_PAGE,
    ];
});
