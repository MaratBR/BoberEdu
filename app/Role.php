<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $timestamps = false;

    protected $fillable = ['name', 'description'];
}
