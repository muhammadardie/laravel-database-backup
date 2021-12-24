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
        $request['password'] = bcrypt($request['password']);
        $store    = $this->store($request->except(['photo', 'password_confirmation']),$callback);
        if($store){
            $files    = array(
                        [
                            'field' => 'photo',
                            'file'  => $request->file('photo'),
                            'path'  => "user",
                            'name'  => "USER",
                        ],
                    );
            $upload   = $this->upload($store,$files);

            return $upload;
        } else {
            return $store;
        }
    }

    public function updateUser($request, $userId)
    {
        $callback  = true;
        $oldName   = $this->show($userId)->photo;

        unset($request['password_confirmation']);
        
        if($request['password'] == ''){
            unset($request['password']);
        } else {
            $request['password'] = bcrypt($request['password']);
        }

        $update    = $this->update($request->except(['photo', 'password_confirmation']), $userId, $callback);

        if($update){
            $files    = array(
                        [
                            'field' => 'photo',
                            'file'  => $request->file('photo'),
                            'path'  => "user",
                            'name'  => "USER",
                        ],
                    );

            $upload = $this->upload($update, $files, $oldName);

            return $upload;
        }
    }

    public function deleteUSer($userId)
    {
        $fileName    = $this->show($userId)->photo;
        $deleteImage = $this->deleteFile('user/' . $fileName);

        return $this->delete($userId);
    }

    public function datatableUser($request)
    {
        if($request->ajax()){
            $sql_no_urut = $this->getRowNum('users.id', $request);
            $user  = $this->model
                          ->select([
                            \DB::raw($sql_no_urut),
                            'users.id',
                            'users.name',
                            'users.email',
                            'users.role'
                          ]);

            return \DataTables::of($user)
                            ->addColumn('action', function ($user) {
                                $btn_action = '<a data-href="'. route('user.show', $user->id) .'" class="btn btn-action cur-p btn-outline-primary btn-show-datatable" title="Detail"><span class="fa fa-search"></span></a>&nbsp;&nbsp;';

                                if ($user->role === 'User' && $user->id !== \Auth::user()->id) {
                                    $btn_action .= '<a data-href="'. route('user.show', $user->id) .'"
                                                    class="btn btn-action cur-p btn-outline-primary btn-edit-datatable" title="Change">
                                                    <span class="fa fa-edit"></span></a>&nbsp;&nbsp;';

                                    $btn_action .= '<a data-href="'. route('user.show', $user->id) .'" class="btn btn-action cur-p btn-outline-primary btn-delete-datatable" title="Delete">
                                                    <span class="fa fa-trash"></span></a>';
                                }

                                return $btn_action;
                                
                            })
                            ->addColumn('role', function ($user) {
                                $role = ($user->role == 'Admin') ? 
                                    "<span class='badge badge-primary'> Admin </span>" : 
                                    "<span class='badge badge-secondary'> User </span>";

                                return "<center>".$role."</center>";
                            })
                            ->rawColumns(['action', 'role']) // to html
                            ->make(true);
        }
    }
}