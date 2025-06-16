<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Dedoc\Scramble\Attributes\Endpoint;

class TodoController extends Controller
{
    #[Endpoint('Mengambil semua todo milik pengguna yang login')]
    public function index()
    {
        $todos = Todo::where('user_id', Auth::id())->with('category')->get();

        return response()->json([
            'status_code' => 200,
            'message' => 'Todo berhasil diambil',
            'data' => $todos,
        ]);
    }

    #[Endpoint('Membuat todo baru')]
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'is_done' => 'boolean',
            ]);

            $todo = Todo::create([
                'title' => $data['title'],
                'user_id' => Auth::id(),
                'category_id' => $data['category_id'] ?? null,
                'is_done' => $data['is_done'] ?? false,
            ]);

            return response()->json([
                'status_code' => 201,
                'message' => 'Todo berhasil dibuat',
                'data' => $todo,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status_code' => 422,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    #[Endpoint('Mencari todo berdasarkan judul atau kategori')]
    public function search(Request $request)
    {
        $query = $request->input('q');

        $todos = Todo::where('user_id', Auth::id())
            ->where(function ($qbuilder) use ($query) {
                $qbuilder->where('title', 'like', "%{$query}%")
                         ->orWhereHas('category', function ($qcat) use ($query) {
                             $qcat->where('title', 'like', "%{$query}%");
                         });
            })
            ->with('category')
            ->get();

        return response()->json([
            'status_code' => 200,
            'message' => 'Todo berhasil diambil',
            'data' => $todos,
        ]);
    }

    #[Endpoint('Memperbarui todo yang ada')]
    public function update(Request $request, $id)
    {
        try {
            $todo = Todo::where('user_id', Auth::id())->findOrFail($id);

            $data = $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'is_done' => 'boolean',
            ]);

            $todo->update([
                'title' => $data['title'],
                'category_id' => $data['category_id'] ?? $todo->category_id,
                'is_done' => $data['is_done'] ?? $todo->is_done,
            ]);

            return response()->json([
                'status_code' => 200,
                'message' => 'Todo berhasil diperbarui',
                'data' => $todo->fresh(),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status_code' => 404,
                'message' => 'Todo tidak ditemukan',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'status_code' => 422,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    #[Endpoint('Menghapus todo')]
    public function destroy($id)
    {
        try {
            $todo = Todo::where('user_id', Auth::id())->findOrFail($id);
            $todo->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Todo berhasil dihapus',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status_code' => 404,
                'message' => 'Todo tidak ditemukan',
            ], 404);
        }
    }
}
