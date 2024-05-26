<?php

namespace App\Services\User;
use App\Mail\SentMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Service{
    public function store($data, $path= null){
        $pass= Str::random(7);
        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'login'=>$data['login'],
            'password'=>$pass,
        ]);
        Mail::to($data['email'])->send(new SentMail($data['name'], $pass));

        if($data['surname']){
            $user->surname = $data['surname'];
            $user->save();
        }

//        $user = User::create($data);

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
