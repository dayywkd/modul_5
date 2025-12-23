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
    // GET ALL - Sesuai syarat manajemen data relasi
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

    // GET ONE - Menggunakan Slug sebagai identifier unik
    public function show($slug): JsonResponse
    {
        $ticket = Ticket::with('category')->where('slug', $slug)->firstOrFail();
        return response()->json($ticket);
    }

    // CREATE - Terproteksi JWT & Mengelola Relasi
    public function store(Request $req): JsonResponse
    {
        $data = $req->validate([
            'category_id' => 'required|exists:categories,id',
            'movie_title' => 'required|string',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|max:5120',
        ]);

        $data['slug'] = Str::slug($req->movie_title) . '-' . Str::random(5);
        $data['status'] = 'pending';
        $data['user_name'] = $req->user()->name;

        if ($req->hasFile('file')) {
            $data['file_path'] = $req->file('file')->store('tickets', 'public');
        }

        $ticket = Ticket::create($data);

        // Update stok/count pada tabel relasi
        $category = Category::find($data['category_id']);
        if ($category) {
            $category->increment('request_count');
        }

        return response()->json([
            'message' => 'Ticket created successfully',
            'data' => $ticket
        ], 201);
    }

    // UPDATE - Menggunakan Slug & Proteksi Admin
    public function update(Request $req, $slug): JsonResponse
    {
        if ($req->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized: Admin access required'], 403);
        }

        $ticket = Ticket::where('slug', $slug)->firstOrFail();

        $data = $req->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'movie_title' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'status'      => 'sometimes|in:pending,approved,rejected',
            'file'        => 'nullable|file|max:5120',
        ]);

        if (isset($data['movie_title'])) {
            $data['slug'] = Str::slug($data['movie_title']) . '-' . Str::random(5);
        }

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

    // DELETE - PERBAIKAN: Menggunakan $slug agar sesuai dengan URL
    public function destroy(Request $req, $slug): JsonResponse
    {
        // Pastikan proteksi admin
        if ($req->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized: Admin access required'], 403);
        }

        // Cari berdasarkan SLUG, bukan ID agar tidak error
        $ticket = Ticket::where('slug', $slug)->firstOrFail();

        // Update logic tabel relasi
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
