<?php
 
namespace App\View\Composers;
 
use Illuminate\View\View;
use App\Models\User;

class SidebarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $url     = \Request::segments();
		$user    = new User;
		$roleOpt = $user->roleOption();
		
		if(\Auth::user()->role === 'User') unset($roleOpt['Admin']); 

	    $view->with('url',  $url)
	    	 ->with('roleOpt', $roleOpt);
    }
}