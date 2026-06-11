<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Service;
use App\Models\ClientDocument;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $allClients   = Client::all();
        $allServices  = Service::all();
        $allDocuments = ClientDocument::all();

        return view('dashboard', [

            'servicesCount'  => Service::count(),
            'clientsCount'   => Client::count(),
            'pendingCount'   => Client::where('status', 'Pending')->count(),
            'completedCount' => Client::where('status', 'Completed')->count(),

            'allClients'   => $allClients,
            'allServices'  => $allServices,
            'allDocuments' => $allDocuments,

            'searchClients' => $allClients->map(fn($c) => [
                'id'     => $c->id,
                'name'   => $c->client_name,
                'status' => $c->status,
                'type'   => 'client',
                'url'    => route('clients.index'),
            ])->values()->toJson(),

            'searchServices' => $allServices->map(fn($s) => [
                'id'   => $s->id,
                'name' => $s->name,
                'type' => 'service',
                'url'  => route('services.index'),
            ])->values()->toJson(),

            'searchDocuments' => $allDocuments->map(fn($d) => [
                'id'    => $d->id,
                'title' => $d->original_name,
                'type'  => 'document',
                'url'   => route('documents.index'),
            ])->values()->toJson(),

        ]);
    }

    // ─── Create a new client (called from dashboard modal) ───────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name'   => 'required|string|max:255',
            'date_received' => 'required|date',
            'status'        => 'required|in:Pending,On going,Completed',
            'service_id'    => 'nullable|exists:services,id',
        ]);

        $client = Client::create($validated);

        return response()->json(['success' => true, 'client' => $client]);
    }
}