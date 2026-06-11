<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'client_id',
        'file_name',
        'file_path'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}