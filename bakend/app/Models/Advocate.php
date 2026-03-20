<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advocate extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_no',
        'enrollment_no_str',
        'year',
        'name',
        'gender',
        'father_name',
        'bar_association',
        'district',
        'membership_details',
        'address',
    ];
}
