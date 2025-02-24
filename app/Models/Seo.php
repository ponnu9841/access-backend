<?php

namespace App\Models;

use App\Traits\HasUuidsAndHiddenTimestamps;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasUuidsAndHiddenTimestamps;
    protected $table = 'seo';
}
