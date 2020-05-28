<?php

namespace App\Http\ViewComposers;

use App\Models\User;

class NavigationComposer
{
	public function compose($view)
	{
		$url     = \Request::segments();
		$user    = new User;
		$roleOpt = $user->roleOption();
		
	    $view->with('url',  $url)
	    	 ->with('roleOpt', $roleOpt);
	}
}