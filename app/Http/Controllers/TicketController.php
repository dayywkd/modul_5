<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    // GET ALL - Menampilkan data dengan relasi kategori
    public function index(Request $req): JsonResponse
    {
        $limit = $req->limit ?? 10;
        $search = $req->search ?? '';
        $orderBy = $req->orderBy ?? 'id';
        $sortBy = $req->sortBy ?? 'DESC';

        $tickets = Ticket::with('category')
            ->where('movie_title', 'LIKE', "%$search%")
            ->orderBy($orderBy, $sortBy)
            ->paginate($limit);

        return response()->json($tickets);
    }

    // GET ONE - Menggunakan Slug
    public function show($slug): JsonResponse
    {
        $ticket = Ticket::with('category')->where('slug', $slug)->firstOrFail();
        return response()->json($ticket);
    }

    // CREATE - Otomatis status 'pending'
    public function store(Request $req): JsonResponse
    {
        $data = $req->validate([
            'category_id' => 'required|exists:categories,id',
            'movie_title' => 'required|string',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|max:5120',
        ]);

        $data['slug'] = Str::slug($req->movie_title) . '-' . Str::random(5);
        $data['status'] = 'pending'; // User request otomatis pending
        $data['user_name'] = $req->user()->name;

        if ($req->hasFile('file')) {
            $data['file_path'] = $req->file('file')->store('tickets', 'public');
        }

        $ticket = Ticket::create($data);

        // Update count kategori (Relasi sesuai modul UAP)
        $category = Category::find($data['category_id']);
        if ($category) {
            $category->increment('request_count');
        }

        return response()->json([
            'message' => 'Ticket created successfully',
            'data' => $ticket
        ], 201);
    }

    // UPDATE - Proteksi Admin & Mendukung status 'in procces'
    public function update(Request $req, $slug): JsonResponse
    {
        $ticket = Ticket::where('slug', $slug)->firstOrFail();

        // Validasi data
        $rules = [
            'category_id' => 'sometimes|exists:categories,id',
            'movie_title' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|max:5120',
        ];

        // Hanya admin yang bisa mengubah status (in procces ditambahkan)
        if ($req->user()->email === 'admin@example.com') {
            $rules['status'] = 'sometimes|in:pending,approved,in procces,rejected';
        }

        $data = $req->validate($rules);

        // Update slug jika judul berubah
        if (isset($data['movie_title'])) {
            $data['slug'] = Str::slug($data['movie_title']) . '-' . Str::random(5);
        }

        // Penanganan File
        if ($req->hasFile('file')) {
            if ($ticket->file_path && Storage::disk('public')->exists($ticket->file_path)) {
                Storage::disk('public')->delete($ticket->file_path);
            }
            $data['file_path'] = $req->file('file')->store('tickets', 'public');
        }

        $ticket->update($data);

        return response()->json([
            'message' => 'Ticket updated successfully',
            'data' => $ticket
        ]);
    }

    // DELETE - Proteksi Admin
    public function destroy(Request $req, $slug): JsonResponse
    {
        // Proteksi Role sesuai modul
        if ($req->user()->email !== 'admin@example.com') {
            return response()->json(['message' => 'Unauthorized: Admin access required'], 403);
        }

        $ticket = Ticket::where('slug', $slug)->firstOrFail();

        // Update logic tabel relasi (Decrement)
        $category = Category::find($ticket->category_id);
        if ($category) {
            $category->decrement('request_count');
        }

        if ($ticket->file_path && Storage::disk('public')->exists($ticket->file_path)) {
            Storage::disk('public')->delete($ticket->file_path);
        }

        $ticket->delete();

        return response()->json([
            'message' => 'Ticket deleted successfully'
        ]);
    }
}
