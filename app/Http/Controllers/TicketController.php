<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // GET ALL (pagination, search, orderBy, sortBy)
    public function index(Request $req)
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


    // GET ONE
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return response()->json($ticket);
    }


    // CREATE
    public function store(Request $req)
    {
        $ticket = Ticket::create($req->all());
        return response()->json([
            "message" => "Ticket created",
            "data" => $ticket
        ]);
    }


    // UPDATE
    public function update(Request $req, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($req->all());

        return response()->json([
            "message" => "Ticket updated",
            "data" => $ticket
        ]);
    }


    // DELETE
    public function destroy($id)
    {
        Ticket::destroy($id);
        return response()->json(["message" => "Ticket deleted"]);
    }
}
