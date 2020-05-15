<?php

namespace App\Http\Controllers\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    protected $userRepo;
    
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('user_management.user_index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('user_management.user_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $store = $this->userRepo->storeUser($request);

        return redirect()->route('user.index')->with(\Helper::alertStatus('store', $store));
    }

    /**
     * Display the specified resource.
     *
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        $user = $this->userRepo->show($userId);

        return view('user_management.user_show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int
     * @return \Illuminate\Http\Response
     */
    public function edit($userId)
    {
        $user = $this->userRepo->show($userId);

        return view('user_management.user_edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $userId)
    {
        $update = $this->userRepo->updateUser($request, $userId);

        return redirect()->route('user.index')->with(\Helper::alertStatus('update', $update));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId)
    {
       $deleteImage = $this->userRepo->deleteFiles('user', $this->userRepo->show($user)->photo);

       return $this->userRepo->delete($user);
    }

    /**
    * Showing list user by datatable
    * @param $request ajax
    * @return json
    */
    public function ajaxDatatable(Request $request)
    {
        $route = 'user'; // if routing and table have different name

        return $this->userRepo->makeDatatable($request, $route);
    }
}
