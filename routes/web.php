<?php

use App\Models\{
    User,
    Preference
};
use Illuminate\Support\Facades\Route;

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

Route::get('/one-to-one', function () {
    /*Controller*/
    $user = User::first();
    $preferences = $user->preference;
    $data = [
        'background_color' => '#000',
    ];
    /*Verificando se o usuario tem preferencia, atualiza, se não cria*/
    if ($user->preference) {
        $user->preference->update($data);
    } else {
        /*forma 2*/
        $preference = new Preference($data);
        $user->preference()->save($preference);
        /*forma 1*/
        //$user->preference()->create($data);

    }
    $user->refresh();
    var_dump($user->preference);

    $user->preference->delete();
    /*faz um refresh para atualizar usuario com preferencia, mesma coisa de $user = User::first();*/
    $user->refresh();
    dd($user->preference);

    /*Formas de acessar preferencias do usuario*/
    //dd($user->preference);
    //dd($user->preference()->get());
    //dd($user->preference()->first()); //Não recomendado, faz nova consulta banco
});

Route::get('/', function () {
    return view('welcome');
});
