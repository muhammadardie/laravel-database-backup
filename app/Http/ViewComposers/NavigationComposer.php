<?php

namespace App\Http\ViewComposers;

class NavigationComposer
{
	public function compose($view)
	{
		$url = \Request::segments();
	    $view->with('url',  $url);
	}
}