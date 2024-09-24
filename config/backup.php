<?php

return [

    /*
    | Change value below to corresponding path in machine
    | 
    | For windows machine use it in .env like this
    | PG_DUMP_PATH='C:\Program Files\PostgreSQL\15\bin\pg_dump.exe'
    | PSQL_PATH='C:\Program Files\PostgreSQL\15\bin\psql.exe' 
    |
    | Default value below usually used in linux machine
    */

    'pgdump' => env('PG_DUMP_PATH', '/usr/pgsql-13/bin/pg_dump'),
    'psql'   => env('PSQL_PATH', '/usr/pgsql-13/bin/psql'),
];
