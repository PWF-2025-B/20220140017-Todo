<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('todo.index', compact('todos'));
    }

    public function create()
    {
        return view('todo.create');
    }

    public function edit($id)
    {
        $todo = Todo::findOrFail($id);
        return view('todo.edit', compact('todo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => auth()->user()->id,
            'is_done' => false // ✅ default status belum selesai
        ]);

        return redirect()->route('todo.index')->with('success', 'Todo created successfully!');
    }
}