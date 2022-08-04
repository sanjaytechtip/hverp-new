<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable = [
        'block_unblock',
        'register_type',
        'medical_type'
    ];

    protected $casts = [
        'created_date' => 'datetime',
    ];
}
