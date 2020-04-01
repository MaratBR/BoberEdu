<?php

namespace App\Providers\Services\Implementation;

use App\Providers\Services\Abs\ICourseUnitsUpdateResponse;

class UnitsUpdateResponse implements ICourseUnitsUpdateResponse
{
    private $newIds;
    private $updated;
    private $deleted;

    public function __construct($newIds, $updated, $deleted)
    {
        $this->deleted = $deleted;
        $this->newIds = $newIds;
        $this->updated = $updated;
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0)
    {
        return json_encode([
            'created' => $this->newIds,
            'deleted' => $this->deleted,
            'updated' => $this->updated
        ], $options);
    }
}
