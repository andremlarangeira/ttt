<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        if (! Auth::check()) {
            $user = User::create([
                'name' => 'Player '.rand(1, 1000),
            ]);

            Auth::login($user);
        }

        return Inertia::render('Index', [
            'user' => Auth::user(),
        ]);
    }

    public function update(User $user, Request $request)
    {
        $user->update([
            'name' => $request->input('name'),
        ]);

        return redirect()->back();
    }
}
