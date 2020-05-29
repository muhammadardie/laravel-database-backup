<?php

namespace App\Http\Controllers\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\UserRequest;
use App\Http\Requests\EditPasswordRequest;
use App\Http\Requests\EditProfileRequest;

class UserController extends Controller
{
    protected $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roleOpt = $this->userRepository->getModel()->roleOption();

        return view ('user_management.user_index', compact('roleOpt'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $store = $this->userRepository->storeUser($request);
        
        return response()->json(['status' => $store]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        $user = $this->userRepository->show($userId);

        return response()->json($user);
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
        $update = $this->userRepository->updateUser($request, $userId);

        return response()->json(['status' => $update]);
    }

    /**
     * Update password user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function changePassword(EditPasswordRequest $request, $userId)
    {
        $update = $this->userRepository->updateUser($request, $userId);

        return response()->json(['status' => $update]);
    }

    /**
     * Update profile user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function changeProfile(EditProfileRequest $request, $userId)
    {
        $update = $this->userRepository->updateUser($request, $userId);

        return response()->json(['status' => $update]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId)
    {
       return $this->userRepository->deleteUser($userId);
    }

    /**
    * Showing list user by datatable
    * @param $request ajax
    * @return json
    */
    public function ajaxDatatable(Request $request)
    {
        return $this->userRepository->datatableUser($request);
    }
}
