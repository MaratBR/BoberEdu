<?php


namespace App\Http\DTO\Admin;


use App\AuditRecord;
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
        $extra = $this->record->extra;
        $extraJson = json_decode($extra);
        if ($extraJson !== null)
            $extra = $extraJson;

        return [
            $this->record->id,
            $this->record->subject,
            $this->record->subject_type,
            $this->record->user_agent,
            $this->record->comment,
            $this->record->action,
            $extra,
            $this->record->ip,
        ];
    }
}
