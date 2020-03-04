<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string name
 * @property string description
 */
class Role extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'description'];
}
