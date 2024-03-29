<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scheduler extends Model
{
	protected $table   = 'scheduler';
	protected $guarded = [];

    public function storage()
    {
    	return $this->belongsTo('App\Models\Storage', 'storage_id', 'id');
    }

    public function dbSource()
    {
    	return $this->belongsTo('App\Models\DatabaseSource', 'database_source_id', 'id');
    }


}
