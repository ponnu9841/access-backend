<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base;

class Service extends Base
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];
}
