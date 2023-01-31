<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';

    protected $primaryKey = 'id';

    protected $fillable = [
        'credit'
    ];

    protected $guarded = [
        'id',
        '_fk_employee_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'credit' => 'float'
    ];
}
