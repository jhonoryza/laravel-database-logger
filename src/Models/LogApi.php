<?php

namespace Jhonoryza\DatabaseLogger\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogApi extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'url',
        'method',
        'code',
        'header',
        'payload',
        'response',
    ];
}
