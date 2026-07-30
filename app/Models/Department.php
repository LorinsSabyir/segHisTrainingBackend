<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'deptnr',
        'deptid',
        'dept_name',
        'dept_shortname',
        'parent_dept_nr',
        'parent_name'
    ];

    public function doctor()
    {
        return $this->hasMany(User::class, 'dept_id');
    }
}
