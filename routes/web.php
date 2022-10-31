<?php

use App\Models\{
    Course,
    Module,
    Permission,
    User,
    Preference,
    Image
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

Route::get('/one-to-many', function () {
    //$course = Course::create(['name'=> 'Curso laravel']);
    $course = Course::first();
    /*modo de acesso a classe Modulo através do relacionamento com curso*/

    //$course->modules();

    //$course->modules()->create($data);
    $modules = $course->modules;
    //dd($modules);
    //$course->modules()->get();//como um módulo tem muitos cursos desta forma, pode demorar a consulta no bd
    $data = ['name' => 'modulo alteração xxx'];

    if (!$course->modules) {
        $course->modules()->update($data);
    } else {
        $course->modules()->create($data);
    }

    $course->refresh();
    //var_dump($course->modules);


    $course->modules()->delete();
    dd($course->modules);
    $course->refresh();
});

Route::get('/many-to-many', function () {
    //Criando permissoes
    //dd(Permission::create(['name' => 'compras']));

    //Adicionando a permissão 1 para o usuario 1
    $user = User::with('permissions')->find(1);
    //dd($user->permission);

    //pegando a primeira permissão criada e salvando no usuario acima
    //$permission = Permission::find(1);
    //$user->permissions()->save($permission);

    //atribuindo varias permissoes para um usuario
    // $user->permissions()->saveMany([
    //     Permission::find(1),
    //     Permission::find(2),
    //     Permission::find(3),
    // ]);

    //O usuario sincronizara apenas com a permissão 2
    //$user->permissions()->sync([2]);

    //O attach adiciona varias vezes a mesma permissão, o que é ruim,
    //como sabe que o usuario tem a permissão 2, a possibilidade é passar a permissão que o usuario não tem
    //$user->permissions()->attach([1,3]);

    //O detach faz o contrario do attach, remove as permissoes de um id especifico

    $user->permissions()->detach([1]);
    $user->refresh();

    dd($user->permissions);
});

Route::get('/many-to-many-pivot', function () {
    $user = User::with('permissions')->find(1);
    $user->permissions()->attach([
        1 => ['active' => false]
    ]);
    $user->refresh();

    foreach ($user->permissions as $permission) {
        echo "{$permission->name} - {$permission->pivot->active} <br>";
    }
    // dd($user->permissions);
});

Route::get('/one-to-one-polymorphic', function () {
    $user = User::first();
    $data = ['path' => 'path/nome-image.png'];

    //$user->image->delete();

    if ($user->image) {
        $user->image->update($data);
    } else {
        $user->image()->save(new Image()); //quando utiliza o save para salvar é necessario criar a extensão, usar o new
    }


    dd($user->image);
});

Route::get('/', function () {
    return view('welcome');
});
