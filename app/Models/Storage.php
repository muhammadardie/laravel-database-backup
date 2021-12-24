<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Storage extends Model
{
	protected $table   = 'storage';
	protected $guarded = [];

    // datatable dataset that must be generated datatableColumn, scopeDatatableCond, datatableButtons  
	public function datatableColumns()
	{
	    return [
	    		'storage.id',
			    'storage.name',
			    'storage.host',
			    'storage.port'
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

	public function hashPassword($value)
    {
        return Crypt::encryptString($value);
    }

	public function getHashedPasswordAttribute()
    {
        return Crypt::decryptString($this->password);
    }
}
