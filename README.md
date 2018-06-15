# Laravel Login and Register system including Laravel Socialite and Email Confirmation
It has Google, Facebook, Github and Twitter out of box (login or registering by email and name).

## Installation
### Step 1 
Include via Composer

```bash
composer require gurinder/laravel-auth
```
### Step 2 (Optional)
Publish the views(If required to customize), config(Optional)
``` bash
// Optional
php artisan vendor:publish --tag="gauth::views"

// Optional
php artisan vendor:publish --tag="gauth::config"
```
### Step 3
Add following to your User Model Schema
```php
Schema::create('users', function (Blueprint $table) {
    ...
    $table->boolean('email_verified')->default(false);
    $table->string('email_verification_token')->nullable();
    ...
});
```

### Step 4
Get cliend ids and secret for your applications and put in .env file
```bash
FACEBOOK_APP_ID=
FACEBOOK_APP_SECRET=
SOCIALITE_FACEBOOK_CALLBACK="/socialite/facebook/callback"

TWITTER_CLIENT_ID=
TWITTER_CLIENT_SECRET=
TWITTER_ACCESS_TOKEN=
TWITTER_ACCESS_SECRET=
SOCIALITE_TWITTER_CALLBACK="/socialite/twitter/callback"

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_DEVELOPER_KEY=
SOCIALITE_GOOGLE_CALLBACK="${APP_URL}/socialite/google/callback"

GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
SOCIALITE_GITHUB_CALLBACK="/socialite/github/callback"
```

### Step 5
Add Social Providers to `config/service.php`
```php
return [
    
    // ....
    
    'twitter' => [
        'client_id'     => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect'      => env('SOCIALITE_TWITTER_CALLBACK'),
        'access_token'  => env('TWITTER_ACCESS_TOKEN'),
        'access_secret' => env('TWITTER_ACCESS_SECRET'),
    ],

    'github' => [
        'client_id'     => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect'      => env('SOCIALITE_GITHUB_CALLBACK'),
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_APP_ID'),
        'app_id'        => env('FACEBOOK_APP_ID'),
        'client_secret' => env('FACEBOOK_APP_SECRET'),
        'redirect'      => env('SOCIALITE_FACEBOOK_CALLBACK'),
    ],

    'google' => [
        'recaptcha_site_key'   => env('GOOGLE_RECAPTCHA_SITE_KEY'),
        'recaptcha_secret_key' => env('GOOGLE_RECAPTCHA_SECRET_KEY'),
        'client_id'            => env('GOOGLE_CLIENT_ID'),
        'client_secret'        => env('GOOGLE_CLIENT_SECRET'),
        'developer_key'        => env('GOOGLE_DEVELOPER_KEY'),
        'redirect'             => env('SOCIALITE_GOOGLE_CALLBACK')
    ]

];
```
### Step 6
Configure plugin
NOTE: Make sure default role is present in database (If you want you can also use `gurinder/laravel-acl`)
```php
return [
    // Open or Close Registration
    'registration_open'              => true,

    // Name fields to Register, make sure it matches with your user model
    'registration_name_fields'       => [
        // 'name'
        'first_name',
        'last_name',
    ],

    // validation rules for registeration
    // Note: Do not delete data field, its required for registration
    'registration_validateion_rules' => [
        'first_name' => 'required|string',
        'last_name'  => 'required|string',
        'email'      => 'required|string|email|max:255|unique:users',
        'password'   => 'required|string|min:6|max:255|confirmed',
        'data'       => 'string' // Do not delete this field
    ],

    // Redirect path after user is successfulle logged in
    'redirect_path_after_login' => '/login-successful',

    // Redirect path after user is successfulle reseted password
    'redirect_path_after_password_reset' => '/password-reset-done',

    // Redirect path after user registered and email confiremed via mail
    'redirect_path_after_email_confirmation' => '/email-is-confirmed',

    // User model must have a method $user->assignRole($roles)
    // in order to this works
    // And it must accept role slug, role id, roles array, role instance
    // NOTE -> make sure this role exist in database
    'default_roles'                          => ['subscriber'],

    // Email configuration to send welcome and confirmation(email) emails to user
    'email_from' => [
        'name'  => 'Gurinder Laravel Auth',
        'email' => 'noreply@example.com'
    ]

];
```

## Login Process
- User come to login page
- On filling up login form, user will be redirected to `redirect_path_after_login`
- Or if user click social provider, and user email already exists, then also user will be logged in and redirected to `redirect_path_after_login`

## Registeration Process
- User come to registeration page
- On filling up registeration form, user will be sent Email Confirmation to confirm email
- On Email confirmation, user will be redirected to `redirect_path_after_email_confirmation`
- Or if user click social provider, 
- Case 1. if user email already exists, then also user will be logged in and redirected to `redirect_path_after_login`
- Case 2. after social provider callback, new user will be redirected to view to choose password and on completion user will be logged in and email will be automatically verified and redirected to `redirect_path_after_login` 

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.