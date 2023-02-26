<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'typeID',
        'loanID',
        'value',
        'date',
        'userID'
    ];
}
