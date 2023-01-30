<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public $table = 'employees';

    // Fields which can be assigned by an incoming request
    protected $fillable = [
        'first_name',
        'last_name',
        'email_address',
        'mobile_number',
        'pin'
    ];

    // Fields which cannot be assigned
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    // Provides the data type for each field
    protected $casts = [
        'first_name' => 'string',
        'last_name' => 'string',
        'email_address' => 'string',
        'mobile_number' => 'string',
        'pin' => 'string'
    ];
}
