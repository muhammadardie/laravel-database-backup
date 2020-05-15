<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disk extends Model
{
    protected $guarded 					= [];
    protected $revisionCreationsEnabled = true;

    // datatable dataset that must be generated datatableColumn, scopeDatatableCond, datatableButtons  
	public function datatableColumns()
	{
	    return [
	    		'disks.id',
			    'disks.name',
			    'disks.host',
			    'disks.created_at'
			];
	}

	public function datatableButtons()
	{
	    return ['show', 'edit', 'destroy'];
	}
}
