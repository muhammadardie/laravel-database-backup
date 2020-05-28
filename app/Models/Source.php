<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
	protected $guarded = [];

    // datatable dataset that must be generated datatableColumn, scopeDatatableCond, datatableButtons  
	public function datatableColumns()
	{
	    return [
	    		'sources.id',
			    'sources.name',
			    'sources.host',
			    'sources.type'
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

	public function type()
    {
    	// default enum
        // return ['mysql' => 'mysql', 'postgresql' => 'postgresql'];
        return ['postgresql' => 'postgresql'];
    }
}
