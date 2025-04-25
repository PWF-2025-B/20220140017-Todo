<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', 1)
            ->orderBy('name')
            ->paginate(10);
        return view('user.index', compact('users'));
    }
}
