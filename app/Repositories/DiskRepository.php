<?php

namespace App\Repositories;

use App\Models\Disk;

class DiskRepository extends BaseRepository
{
    public function __construct(Disk $disk)
    {
        $this->model = $disk;
    }
}