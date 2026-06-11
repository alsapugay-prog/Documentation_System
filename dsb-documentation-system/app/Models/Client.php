<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ClientDocument; // ← DAGDAG ITO

class Client extends Model
{
    protected $fillable = [
        'service_id',
        'client_name',
        'date_received',
        'status',
        'requirements_checklist',
        'tracker_data',
    ];

    protected $casts = [
        'requirements_checklist' => 'array',
        'tracker_data'           => 'array',
        'date_received'          => 'date',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // ← DAGDAG ITO
    public function documents()
    {
        return $this->hasMany(ClientDocument::class);
    }
}