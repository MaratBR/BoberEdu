<?php

namespace App\Http\Controllers;

use App\Lesson;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LessonTrait extends CRUDTrait
{
    protected function getListColumns(Request $request): array
    {
        if ($request->user() && $request->user()->isAdmin())
            return parent::getColumns($request);
        return ['title', 'unit_id', 'id', 'order_num', 'created_at', ];
    }

    protected function scope(Builder $q, Request $request): ?Builder
    {
        if ($request->user() && $request->user()->isAdmin())
            return $q;
        return $q
            ->whereHas('unit.course', function (Builder $q) {
                $q->where('available', '=', true);
            });
    }

    public function __construct()
    {
        $this->configure(
            Lesson::class,
            Lesson::$rules,
            Lesson::$updateRules,
            'lesson'
        );
    }
}
