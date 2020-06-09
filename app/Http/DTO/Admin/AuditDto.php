<?php


namespace App\Http\DTO\Admin;


use App\AuditRecord;
use App\Utils\Audit\Audit;
use App\Utils\Audit\IDisplayName;
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
        $subject = $this->record->subject;
        if ($subject instanceof IDisplayName) {
            $displayName = $subject->getDisplayName();
        } else {
            $displayName = $this->record->subject_type . '#' . $this->record->subject_id;
        }
        $subject = [
            'type' => $this->record->subject_type,
            'id' => $this->record->subject_id,
            'display' => $displayName
        ];

        return [
            'id' => $this->record->id,
            'sub' => $subject,
            'ua' => $this->record->user_agent,
            'c' => $this->record->comment,
            'a' => Audit::getTypeRepresentation($this->record->action),
            'ex' => $this->record->extra,
            'ip' => $this->record->ip,
            'user' => $this->record->user_id === null ? null : [
                $this->record->user->id,
                $this->record->user->name
            ]
        ];
    }
}
