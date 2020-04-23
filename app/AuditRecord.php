<?php

namespace App;

use App\Http\DTO\TeacherDto;
use App\Utils\Audit\AuditRecordBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @method static AuditRecord create(array $array)
 */
class AuditRecord extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'action', 'extra', 'subject', 'ip', 'user_id', 'comment', 'user_agent'
    ];

    public static function builder(): AuditRecordBuilder
    {
        return new AuditRecordBuilder();
    }

    public static function make(User $actor, Request $request, string $action): AuditRecordBuilder
    {
        return self::builder()
            ->actor($actor)
            ->request($request)
            ->action($action);
    }
}
