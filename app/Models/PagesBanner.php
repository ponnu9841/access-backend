<?php

namespace App\Models;

use App\Traits\HasUuidsAndHiddenTimestamps;
use Illuminate\Database\Eloquent\Model;

class PagesBanner extends Model
{
    use HasUuidsAndHiddenTimestamps;
    protected $table = 'pages_banner';
}
