<?php


namespace App\Utils\Audit;


use App\AuditRecord;
use App\Exceptions\Audit\InvalidAuditRecord;
use App\User;
use Illuminate\Http\Request;

class AuditRecordBuilder
{
    private $extra = null;
    private $actorId = null;
    private $comment = null;
    private $ip = null;
    private $action = null;
    private $subject = null;
    private $userAgent = null;


    public function __construct()
    {
    }

    public function data($data): self
    {
        $this->extra = $data;
        return $this;
    }

    public function actor(User $actor): self
    {
        $this->actorId = $actor->id;
        return $this;
    }

    public function comment(string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function ip(string $ip): self
    {
        $this->ip = $ip;
        return $this;
    }

    public function subject($subject): self
    {
        if (!is_string($subject))
            $subject = strval($subject);
        $this->subject = $subject;
        return $this;
    }

    public function action(string $action): self
    {
        $this->action = $action;
        return $this;
    }

    public function userAgent(string $userAgent): self
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function request(Request $request): self
    {
        return $this->userAgent($request->userAgent())->ip($request->ip());
    }

    public function build(): AuditRecord
    {
        if ($this->actorId == null)
            throw new InvalidAuditRecord("Audit record must have actor set");


        if ($this->ip == null)
            throw new InvalidAuditRecord("Audit record must have ip address set");


        if ($this->action == null)
            throw new InvalidAuditRecord("Audit record must have action name set");

        return AuditRecord::create([
            'subject' => $this->subject,
            'user_id' => $this->actorId,
            'ip' => $this->ip,
            'action' => $this->action,
            'comment' => $this->comment,
            'extra' => $this->extra,
            'user_agent' => $this->userAgent
        ]);
    }
}
