<?php

namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Base
{
    use HasFactory;
    
     // Hide timestamps when retrieving data
     protected $hidden = ['created_at', 'updated_at'];
}
