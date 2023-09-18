<?php

use Illuminate\Routing\Router;
use Morris\Core\Actions\Auth\RegisterUser;
use Morris\Core\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

$router = app(Router::class);

$router->middleware("throttle:api")->group(
    function (Router $router): void {
        $router->post("/register", RegisterUser::class)
            ->name("register");
    },
);
