<?php

namespace App\Services;

abstract class BaseService
{
    public static function executeCommand($command)
    {
        exec($command, $output, $status);

        return $status === 0 ? $output : [];  // exec is successful only if the $status was set to 0. then when its failed just return empty array
    }
}