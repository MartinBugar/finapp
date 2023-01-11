<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'value',
        'type',
        'typeID',
        'date',
        'userID',
        'pdf',
    ];
}
