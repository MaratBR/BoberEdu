<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileInfo extends Model
{
    protected $fillable = [
        'original_name', 'sys_name', 'user_id', 'size',
        'mime', 'about'
    ];
}
