<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'fechaVencimiento',
        'cliente_id'
    ];

    public function cliente() 
    {
        return $this->belongsTo(Cliente::class);
    }
}
