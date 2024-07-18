<?php

use \HamidRrj\LaravelDatatable\Tests\Models\User;
use \HamidRrj\LaravelDatatable\Facades\DatatableFacade;

it('can shit', function() {

    $query = User::query();

    DatatableFacade::run(
        $query,
        $request
    );

    \Pest\Laravel\assertDatabaseHas('users', [
        'name' => $user->name,
        'username' => $user->username,
        'email' => $user->email,
    ]);

});
