<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    // GET ALL (pagination, search, orderBy, sortBy)
    public function index(Request $req): JsonResponse
    {
        $limit = $req->limit ?? 10;
        $search = $req->search ?? '';
        $orderBy = $req->orderBy ?? 'id';
        $sortBy = $req->sortBy ?? 'ASC';

        $tickets = Ticket::where('movie_title', 'LIKE', "%$search%")
            ->orderBy($orderBy, $sortBy)
            ->paginate($limit);

        return response()->json($tickets);
    }

    // GET ONE (by slug)
    public function show($slug): JsonResponse
    {
        $ticket = Ticket::where('slug', $slug)->firstOrFail();
        return response()->json($ticket);
    }

    // CREATE
    public function store(Request $req): JsonResponse
    {
        $data = $req->validate([
            'category_id' => 'required|exists:categories,id',
            'movie_title' => 'required|string',
            'description' => 'nullable|string',
            'studio' => 'nullable|string',
            'seat' => 'nullable|string|max:10',
            'show_time' => 'nullable|date',
            'price' => 'nullable|integer',
            'user_name' => 'nullable|string',
            'event_name' => 'nullable|string',
            'event_description' => 'nullable|string',
            // file optional, max size 5120 KB = 5 MB
            'file' => 'nullable|file|max:5120',
        ]);

        if ($req->hasFile('file')) {
            $path = $req->file('file')->store('tickets', 'public');
            $data['file_path'] = $path;
        }

        $ticket = Ticket::create($data);

        return response()->json([
            "message" => "Ticket created",
            "data" => $ticket
        ], 201);
    }

    // UPDATE
    public function update(Request $req, $slug): JsonResponse
    {
        $ticket = Ticket::where('slug', $slug)->firstOrFail();

        $data = $req->validate([
            'movie_title' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'category_id' => 'sometimes|exists:categories,id',
            'file' => 'nullable|file|max:5120',
        ]);

        if ($req->hasFile('file')) {
            // delete old file if exists
            if ($ticket->file_path && Storage::disk('public')->exists($ticket->file_path)) {
                Storage::disk('public')->delete($ticket->file_path);
            }
            $path = $req->file('file')->store('tickets', 'public');
            $data['file_path'] = $path;
        }

        $ticket->update($data);

        return response()->json([
            "message" => "Ticket updated",
            "data" => $ticket
        ]);
    }

    // DELETE
    public function destroy($slug): JsonResponse
    {
        $ticket = Ticket::where('slug', $slug)->firstOrFail();

        // delete file if exists
        if ($ticket->file_path && Storage::disk('public')->exists($ticket->file_path)) {
            Storage::disk('public')->delete($ticket->file_path);
        }

        $ticket->delete();

        return response()->json(["message" => "Ticket deleted"]);
    }
}
