<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function storeUser($request)
    {
        $callback = true;
        $store    = $this->store($request->all(),$callback);

        if($store){
            return $this->manageFiles($store, $request);
        }

        return false;
    }

    public function updateUser($request, $userId)
    {
        $callback = true;
        $oldName  = $this->show($userId);
        $update   = $this->update($request->all(), $userId, $callback);
        
        if($update){
            return $this->manageFiles($update, $request, $oldName);
        }

        return false;
    }

    public function manageFiles($crud, $request, $oldName=NULL)
    {
        $photo    = array(
                        [
                            'field' => 'photo',
                            'file'  => $request->file('photo'),
                            'path'  => "user",
                            'name'  => "USER",
                        ],
                    );

        return $this->uploadFiles($crud, $photo, optional($oldName)->photo);
    }
}