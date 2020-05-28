<?php

namespace App\Services;

use App\Models\Disk;

class DiskService extends BaseService
{
    public function __construct(Disk $disk)
    {
        $this->model = $disk;
    }
}