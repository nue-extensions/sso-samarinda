SSO-Samarinda untuk Nue
======

Integrasi SSO-Samarinda ke aplikasi kamu.

![ss-sso-samarinda](https://raw.githubusercontent.com/novay/imagehost/master/github/nue-sso-samarinda.png)

## Instalasi

1. Install via Composer :
```bash
composer require nue-extensions/sso-samarinda
```

2. Munculin menu SSO-Samarinda ini di sidebar Nue :

```bash
php artisan nue:import sso-samarinda
```

3. Update `table users`

```bash
php artisan migrate --path=app/Nue/Extensions/nue-extensions/sso-samarinda/database/migrations/2014_10_12_000000_alter_users_table.php
```
Perintah ini akan menyisipkan field baru dengan nama `uid` dalam tabel `users` kamu. Kamu bisa menambahkan field `uid` secara manual tanpa harus menggunakan perintah ini.

## Konfigurasi

Tambahin konfigurasi extension berikut di dalam file konfigurasi `config/nue.php` kayak gini:

```php
'extensions' => [

	'sso-samarinda' => [
        // Arahkan kemana Anda akan tuju setelah login berhasil
        'redirect_to' => '/home', 

        // Pilih guard auth default yang dipakai
        'guard' => 'web', 

        // Beberapa parameter yang dibutuhkan untuk broker. Bisa ditemukan di 
        // https://sso.samarindakota.go.id
        'server_url' => env('SSO_SERVER_URL', null),
        'broker_name' => env('SSO_BROKER_NAME', null),
        'broker_secret' => env('SSO_BROKER_SECRET', null),

        // Tentukan Model User yang dipakai
        'model' => '\App\Models\User'
    ],

],
```

## Penggunaan

1. Setelah melakukan konfigurasi diatas, kamu bisa menambahkan 3 opsi baru dalam file `env` kamu :
```shell
SSO_SERVER_URL=https://sso.samarindakota.go.id
SSO_BROKER_NAME=
SSO_BROKER_SECRET=
```
`SSO_SERVER_URL` berisi URI dari SSO Samarinda. `SSO_BROKER_NAME` dan `SSO_BROKER_SECRET` harus diisi sesuai dengan data aplikasi yang didaftarkan di https://sso.samarindakota.go.id.

2. Kustom Middleware bawaan SSO-Samarinda :

Apabila dalam implementasinya Anda ingin melakukan penyimpanan sesi atau melakukan manipulasi pada models **User**, Anda juga bisa melakukan custom pada middleware yang telah disediakan. Contohnya:

a) Buat Middleware Baru

```shell
$ php artisan make:middleware SSOAutoLogin
```

b) Extend **Default Middleware** ke **Custom Middleware**

```php
<?php

namespace App\Http\Middleware;

use Nue\SSOSamarinda\Http\Middleware\SSOAutoLogin as Middleware;
use App\Models\User;

class SSOAutoLogin extends Middleware
{
    /**
     * Manage your users models as your default credentials
     *
     * @param Broker $response
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleLogin($response)
    {
        $user = User::updateOrCreate(['uid' => $response['data']['id']], [
            'name' => $response['data']['name'], 
            'email' => $response['data']['email'], 
            'password' => 'default', 
        ]);

        auth()->login($user);

        return;
    }
}
```

c) Edit **Kernel.php**

```php
protected $middlewareGroups = [
    'web' => [
        ...
        // \Nue\SSOSamarinda\Http\Middleware\SSOAutoLogin::class,
        \App\Http\Middleware\SSOAutoLogin::class,
    ],

    'api' => [
        ...
    ],
];
```

3. Implementasi pada `Views`

a) Login

```html
<a href="{{ route('sso.authorize') }}">Login</a>
```

b) Logout

```html
<a href="{{ route('sso.logout') }}">Logout</a>
```

c) Manual Usage (Optional)

Untuk penggunaan secara manual, Anda bisa menyisipkan potongan script berikut kedalam fungsi login dan logout pada class controller Anda.
```php
protected function attemptLogin(Request $request)
{
    $broker = new \Nue\SSOSamarinda\Service\Broker;
    
    $credentials = $this->credentials($request);
    return $broker->login($credentials['username'], $credentials['password']);
}

public function logout(Request $request)
{
    $broker = new \Nue\SSOSamarinda\Service\Broker;
    $broker->logout();
    
    $this->guard()->logout();
    $request->session()->invalidate();
    
    return redirect('/');
}
```


## Lisensi

**SSO-Samarinda** ini dikembangin dengan [Lisensi MIT](LICENSE.md). Artinya kamu bebas menggunakannya baik untuk kepentingan pribadi maupun komersil. Enjoy!
