<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Radiology extends Model
{
    /** @use HasFactory<\Database\Factories\RadiologyFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'test',
        'group_code',
        'group',
        'section_code',
        'section',
    ];
}
