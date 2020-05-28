<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disk extends Model
{
	protected $guarded = [];

    // datatable dataset that must be generated datatableColumn, scopeDatatableCond, datatableButtons  
	public function datatableColumns()
	{
	    return [
	    		'disks.id',
			    'disks.name',
			    'disks.host',
			    'disks.port'
			];
	}

	public function scopeDatatableCond($query)
    {
        return null;
    }

	public function datatableButtons()
	{
	    return ['show', 'edit', 'destroy'];
	}
}
