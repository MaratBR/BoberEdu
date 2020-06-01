<?php

namespace App;

use App\Utils\Audit\AuditRecordBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @method static AuditRecord create(array $array)
 * @property int id
 * @property int action
 * @property int user_id
 * @property string extra
 * @property string subject
 * @property string|null subject_type
 * @property string ip
 * @property string user_agent
 * @property string|null comment
 */
class AuditRecord extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'action', 'extra', 'subject', 'ip', 'user_id', 'comment', 'user_agent', 'subject_type'
    ];

    protected $casts = [
        'extra' => 'json'
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

    public static function by(User $user)
    {
        return self::builder()->actor($user);
    }

}
