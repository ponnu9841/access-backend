<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimonial extends Base
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];
}
