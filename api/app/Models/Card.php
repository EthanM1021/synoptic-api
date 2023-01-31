<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'credit'
    ];

    protected $guarded = [
        '_fk_employee_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'id' => 'string',
        'credit' => 'float'
    ];
}
