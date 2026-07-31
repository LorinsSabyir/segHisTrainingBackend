<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    //  TODO: Add the data
    protected $fillable = [
        'personnel_id',
        'name_first',
        'name_last',
        'name_middle',
        'name_suffix',
        'role',
        'email',
        'password',
    ];

    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function nursePatientLog()
    {
        return $this->hasMany(Patient::class, 'nurse_id');
    }

    public function nursePatientEncounter()
    {
        return $this->hasMany(PatientEncounter::class, 'nurse_id');
    }

    public function doctorPatientEncounter()
    {
        return $this->hasMany(PatientEncounter::class, 'doctor_id');
    }
}
