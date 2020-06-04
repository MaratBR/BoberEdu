<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FileInfo
 * @package App
 * @property string original_name
 * @property string sys_name
 * @property int user_id
 * @property int size
 * @property string mime
 * @property string about
 * @property int id
 */
class FileInfo extends Model
{
    protected $fillable = [
        'original_name', 'sys_name', 'user_id', 'size',
        'mime', 'about'
    ];
}
