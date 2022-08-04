<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    protected $table = 'vendors';
    protected $fillable = [
        'block_unblock',
        'register_type',
        'medical_type'
    ];

    protected $casts = [
        'created_date' => 'datetime',
    ];
}
