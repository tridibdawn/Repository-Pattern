### Laravel Repository Pattern

Normal Laravel application is based upon __MVC (Model-View-Controller)__ design pattern, which derives that all the database relation are established in `Model` layer and `Controller` handles all responses from client and server and give output as per defined logic.

As Software Development Industry grows the maintainability of code became better and design patterns are modified too. One of those design pattern is __`Repository Pattern`__. The diagram given below describes the workflow of `Repository Pattern`.

<p align="center">
<img src="public/screenshots/Repository Pattern.jpg" alt="Repository Pattern" width="500">
</p>

##### Repository Layer

The first layer of __`Repository Pattern`__ is __Repository__. This layer directly deals with __Model__. All the database related operations must be performed here.

###### Example
- __Model (User.php)__
```php
<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
```
- __Repository (UserRepository.php)__

Like `Model` and `Controller` __Repository__ doesn't have a command to make one __Repository__. For that you have to create the directory of the __Repository__ in the `App` directory like, `App\Repositories\UserRepository.php`. The code of the repository is like the code mentioned below,

```php
<?php
namespace App\Repositories;

use App\User;
use App\Interfaces\UserInterface;

class UserRepository implements UserInterface
{
    protected $user;
    
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    
    public function index()
    {
        $users = $this->user::all();
        return $users;
    }
    
    public function store($data)
    {
        $user = $this->user::create($data);
        return $user;
    }
    
    public function show($id)
    {
        $user = $this->user::findOrFail($id);
        return $user;
    }
    
    public function update($data, $id)
    {
        $user = $this->user::findOrFail($id);
        $user->fill($data);
        $user->save();
        return $user;
    }
    
    public function destroy($id)
    {
        $user = $this->user::findOrFail($id);
        $user->delete();
        return $user;
    }
}
```

- __Interface (UserInterface.php)__

To create an __Interface__ say, __UserInterface.php__ you again have to create a directory and `interface` manually as `App\Interfaces\UserInterface.php`. You must declare all functions of your `Repository` here. For e.g. if you are writing `UserInterface.php` then all fuctions of `UserRepository.php` must be declared here first. The code of in this example is as follows,

```php
<?php
namespace App\Interfaces;

interface UserInterface
{
    public function index();
    public function store($data);
    public function show($id);
    public function update($data, $id);
    public function destroy($id);
}
```
<h4> Interface & Repository bind</h4>

This __Repository Pattern__ won't work if you haven't bind your __Interface__ and __Repository__ properly. Here in this case an example with step by step is given, how to bind each `Repository` and `Interface`. The binding of `UserInterface.php` and `UserRepository.php` is as follows,

1. Go to `App\Providers\AppServiceProvider.php`.
2. Add a line `$this->app->bind('App\Interfaces\UserInterface', 'App\Repositories\UserRepository');` inside register method like below given code.

__(AppServiceProvider.php) before__
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
```
__(AppServiceProvider.php) after adding bind__
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Interfaces\UserInterface', 'App\Repositories\UserRepository');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
```
- __Service (UserService.php)__

Add a directory inside `App` directory like `App\Services\UserService.php` and add the code in following mannar,
```php
<?php
namespace App\Services;

use App\Interfaces\UserInterface;

class UserService
{
    protected $user;
    
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
    
    public function index()
    {
        $users = $this->user->index();
        return $users;
    }
    
    public function store($data)
    {
        $user = $this->user->store($data);
        return $user;
    }
    
    public function show($id)
    {
        $user = $this->user->show($id);
        return $user;
    }
    
    public function update($data, $id)
    {
        $user = $this->user->update($data, $id);
        return $user;
    }
    
    public function destroy($id)
    {
        $user = $this->user->destroy($id);
        return $user;
    }
}
```
- __Controller (UserController.php)__
Controller will be generated as per Laravel default rule, in this case using `php artisan make:controller UserController -r` for resourceful ___UserController___. The controller code is as follows,
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected $user;
    public function __construct(UserService $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user->index();
        return view('users.index', [ 'users' => $users ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->user->create($request->all());
        return redirect('/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->user->show($id);
        return view('users.show', [ 'user' => $user ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->user->show($id);
        return view('users.edit', [ 'user' => $user ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->user->update($request->all(), $id);
        return redirect('/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->user->destroy($id);
        return redirect('/users');
    }
}
```
- __Helper (Helper.php)__

`Helper` completely justifies its name. Any additional operation other than `CRUD` operation can be written in `helper`. Just create a directory as __`App\Helpers\Helper.php`__ and write your code inside it. For example as the code given below,

```php
<?php
use Illuminate\Support\Facades\DB;

if (!function_exists('user_counts'))
{
    function user_counts()
    {
        return DB::table('users')->count();
    }
}
```
After writing this in _`Helper.php`_ you can directly call it in _controller_ and return its value directly to the view by storing the return value of the `helper` method into a _variable_.

###### COMMON ISSUES FOR HELPER
- Even if you have done the steps correctly, you can get error like, Seems like `Call to undefined function App\Http\Controllers\user_counts()`. To fix this problem, you just have to goto your `composer.json` file and look for,

```json
"autoload": {
        "psr-4": {
            "App\\": "app/"
        },
        "classmap": [
            "database/seeds",
            "database/factories"
        ]
    },
```

And add the following lines into `"autoload"` object

```json
"files": [
            "app/helpers/helper.php"
        ]
```

After adding this your `"autoload"` object should look like as follows,

```json
"autoload": {
        "psr-4": {
            "App\\": "app/"
        },
        "classmap": [
            "database/seeds",
            "database/factories"
        ],
        "files": [
            "app/helpers/helper.php"
        ]
    },
```
After this just run `composer dump-autoload` into your terminal in the root project directory.

### Note:

Although, all the fundamentals and procedures of ___`Repository Pattern`___ is described in above instructions but still there is one more _standard_ that developers should follow to write clean code, i.e. _"keep your controller as light-weight as possible"_ for there is one more step that you can include is write your validation in ___Request___ for that you can follow <a href="https://laravel.com/docs/5.8/validation">Laravel Official Documentation</a> or by searching online _"laravel request validation"_. 

### Conclusion
Hope the content of this tutorial is useful for the readers. Happy Coding...

## Application Setup Instructions

1. `git clone https://github.com/tridibdawn/Repository-Pattern.git`
2. `git checkout -b "<your-branch>"`
3. copy _.env.example_ into _.env_ 
4. Setup _database_ in _.env_ 
5. Change _CACHE_DRIVER=file_ to _CACHE_DRIVER=array_ 
6. Run `php artisan config:cache`
7. Run `composer install`
8. Run `php artisan key:generate`
9. `php artisan serve`