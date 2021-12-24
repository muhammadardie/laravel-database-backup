<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class DatabaseSource extends Model
{
	protected $guarded = [];

    // datatable dataset that must be generated datatableColumn, scopeDatatableCond, datatableButtons  
	public function datatableColumns()
	{
	    return [
	    		'database_sources.id',
			    'database_sources.name',
			    'database_sources.host',
			    'database_sources.type'
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

    public function hashPassword($value)
    {
        return Crypt::encryptString($value);
    }

	public function getHashedPasswordAttribute()
    {
        return Crypt::decryptString($this->password);
    }
}
