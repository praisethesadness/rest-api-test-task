<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{        
    public function store(CreateUserRequest $request)
    {
        $user = User::create($request->validated());
        auth()->login($user);

        return $user;
    }
    
    public function show()
    {
        return auth()->user();
    }

    public function update(UpdateUserRequest $request)
    {
        $user = User::find(auth()->id());
        $user->update($request->validated());

        return $user;
    }
    
    public function destroy()
    {
        User::find(auth()->id())->delete();
    }
}
