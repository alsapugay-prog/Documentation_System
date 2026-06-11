<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Client; // ← DAGDAG ITO

class ServiceController extends Controller
{
    /**
     * I-display ang listahan ng mga Services.
     */
    public function index()
    {
        $services = Service::all();
        
        return view('services.index', compact('services'));
    }

    /**
     * I-save ang ginawang bago ng user sa database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'service_type_id' => 'required|string|max:255',
            'primary_contact' => 'required|string|max:255',
        ]);

        // 1. I-save ang service
        $service = Service::create([
            'name'            => $validated['name'],
            'service_type_id' => $validated['service_type_id'],
            'primary_contact' => $validated['primary_contact'],
        ]);

        // 2. Auto-create ng default client na naka-link sa bagong service
        //    Ito ang lalabas agad kapag pinindot ang "View Clients"
        Client::create([
            'service_id'    => $service->id,
            'client_name' => $service->primary_contact,
            'date_received' => now()->toDateString(),
            'status'        => 'Pending',
        ]);

        return response()->json([
            'success' => true,
            'service' => $service
        ]);
    }
}