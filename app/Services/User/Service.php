<?php

namespace App\Services\User;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class Service{
    public function store($data, $path= null){
        $user = User::create($data);

        if($path){
            $relativePath = 'uploads/' . basename($path);
            $user->avatar = $relativePath;
            $user->save();
        }
    }

    public function update($data,  $path= null){
        $user = auth()->user();
        $oldAvatar = basename($user->avatar);
        if(!$oldAvatar){
            $user->update($data);
        }else{
            $user->update($data);
            Storage::delete($oldAvatar);
        }

//        $user->name = $data->name;
//        $user->surname = $data->surname;
//        $user->login = $data->login;
//        $user->password = $data->password;
//        $user->email = $data->email;
//        $user->avatar = $data->avatar;
//        $user->save();
    }
}
