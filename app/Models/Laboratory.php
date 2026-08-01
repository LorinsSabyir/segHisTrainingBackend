<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    /** @use HasFactory<\Database\Factories\LaboratoryFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'test',
        'section_code',
        'section',
        'opd',
        'ipd',
    ];
}
