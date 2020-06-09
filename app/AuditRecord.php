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
 * @property array|null extra
 * @property Model|null subject
 * @property string|null subject_type
 * @property string ip
 * @property string user_agent
 * @property string|null comment
 * @property int subject_id
 * @property User user
 */
class AuditRecord extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'action', 'extra', 'subject_id', 'ip', 'user_id', 'comment', 'user_agent', 'subject_type'
    ];

    protected $casts = [
        'extra' => 'json'
    ];

    public static function make(User $actor, Request $request, string $action): AuditRecordBuilder
    {
        return self::builder()
            ->actor($actor)
            ->request($request)
            ->action($action);
    }

    public static function builder(): AuditRecordBuilder
    {
        return new AuditRecordBuilder();
    }

    public static function log()
    {
        return self::query()->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

}
