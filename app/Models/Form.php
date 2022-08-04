<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $table = 'tbl_form';
    protected $fillable = [
        'form_name',
        'module_name',
        'table_name'
    ];

    protected $casts = [
        'created_date' => 'datetime',
    ];
}
