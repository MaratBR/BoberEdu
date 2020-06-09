<?php


namespace App\Http\DTO\Admin;


use App\AuditRecord;
use App\Utils\Audit\Audit;
use Illuminate\Contracts\Support\Arrayable;

class AuditDto implements Arrayable
{
    private $record;

    public function __construct(AuditRecord $record)
    {
        $this->record = $record;
    }

    public function toArray()
    {
        return [
            'id' => $this->record->id,
            'sub' => $this->record->subject,
            'typ' => $this->record->subject_type,
            'ua' => $this->record->user_agent,
            'c' => $this->record->comment,
            'a' => Audit::getTypeRepresentation($this->record->action),
            'ex' => $this->record->extra,
            'ip' => $this->record->ip,
            'user' => [
                $this->record->user->id,
                $this->record->user->name,
            ]
        ];
    }
}
