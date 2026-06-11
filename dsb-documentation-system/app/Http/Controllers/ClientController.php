<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientDocument;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    // ─── All available requirements (master list) ───────────────────────────
    const REQUIREMENTS = [
        'Tax Declaration',
        'Birth Certificate',
        'Survey Plan',
        'ID Copy',
        'Land Title Copy',
        'Barangay Clearance',
        'SPA',
    ];

    // ─── List clients ────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Client::with('documents')->latest();

        if ($request->filled('search')) {
            $query->where('client_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        $clients      = $query->get();
        $requirements = self::REQUIREMENTS;

        return view('clients.index', compact('clients', 'requirements'));
    }

    // ─── Create a new client ─────────────────────────────────────────────────
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

    // ─── Documents / Tracker page ─────────────────────────────────────────
    public function documents(Request $request)
    {
        $query = Client::with('service')->latest();

        if ($request->filled('search')) {
            $query->where('client_name', 'LIKE', '%' . $request->search . '%');
        }

        return view('documents', [
            'clients' => $query->get(),
        ]);
    }

    // ─── Save tracker data (agencies + docs + notes) ──────────────────────
    public function updateTracker(Request $request, Client $client)
    {
        $client->update([
            'tracker_data' => [
                'agencies' => $request->input('agencies', []),
                'docs'     => $request->input('docs',     []),
                'notes'    => $request->input('notes',    ''),
            ]
        ]);

        return response()->json(['success' => true]);
    }

    // ─── Update client details (name, date_received, status) ────────────────
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'client_name'   => 'required|string|max:255',
            'date_received' => 'required|date',
            'status'        => 'required|in:Pending,On going,Completed',
        ]);

        $client->update($validated);

        return response()->json(['success' => true, 'message' => 'Client updated successfully.']);
    }

    // ─── Save requirements checklist ─────────────────────────────────────────
    public function updateRequirements(Request $request, Client $client)
    {
        $validated = $request->validate([
            'requirements_checklist'   => 'nullable|array',
            'requirements_checklist.*' => 'string|in:' . implode(',', self::REQUIREMENTS),
        ]);

        $client->update([
            'requirements_checklist' => $validated['requirements_checklist'] ?? [],
        ]);

        return response()->json(['success' => true, 'message' => 'Requirements saved.']);
    }

    // ─── Upload files ─────────────────────────────────────────────────────────
    public function uploadDocument(Request $request, Client $client)
    {
        $request->validate([
            'files'   => 'required|array',
            'files.*' => 'file|max:10240',
        ]);

        $uploaded = [];

        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            $storedName   = $file->hashName();
            $path         = $file->store("clients/{$client->id}/documents", 'public');

            $doc = ClientDocument::create([
                'client_id'     => $client->id,
                'original_name' => $originalName,
                'stored_name'   => $storedName,
                'file_path'     => $path,
                'mime_type'     => $file->getMimeType(),
                'file_size'     => $file->getSize(),
            ]);

            $uploaded[] = [
                'id'            => $doc->id,
                'original_name' => $doc->original_name,
                'human_size'    => $doc->human_size,
                'icon_class'    => $doc->icon_class,
                'download_url'  => route('clients.documents.download', [$client, $doc]),
                'delete_url'    => route('clients.documents.destroy',  [$client, $doc]),
                'mime_type'     => $doc->mime_type,
            ];
        }

        return response()->json(['success' => true, 'documents' => $uploaded]);
    }

    // ─── Download a document ──────────────────────────────────────────────────
    public function downloadDocument(Client $client, ClientDocument $document)
    {
        abort_unless($document->client_id === $client->id, 403);

        return Storage::disk('public')->download($document->file_path, $document->original_name);
    }

    // ─── Delete a document ────────────────────────────────────────────────────
    public function destroyDocument(Client $client, ClientDocument $document)
    {
        abort_unless($document->client_id === $client->id, 403);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return response()->json(['success' => true]);
    }

    // ─── Delete a client ─────────────────────────────────────────────────────
    public function destroy(Client $client)
    {
        foreach ($client->documents as $doc) {
            Storage::disk('public')->delete($doc->file_path);
        }

        $client->delete();

        return response()->json(['success' => true]);
    }
}