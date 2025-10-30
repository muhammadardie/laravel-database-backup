<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('backupDb:daily')->daily();