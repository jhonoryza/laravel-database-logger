<?php

namespace Jhonoryza\DatabaseLogger\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogApp extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'message',
        'context',
        'level',
        'level_name',
        'channel',
        'record_datetime',
        'extra',
        'formatted',
        'remote_addr',
        'user_agent',
    ];
}
