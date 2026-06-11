<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'service_type_id',
        'primary_contact',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}