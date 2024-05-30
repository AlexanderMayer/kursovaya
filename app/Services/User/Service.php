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

        $name= $data['name'];
        $email= $data['email'];

        Mail::to($email)->send(new SentMail($name, $pass));


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

    public function update($data, $photo, $newPass= false){
        $user = auth()->user();
        $user->update($data);

        if($newPass){

            $user->password= $newPass;
            $user->save();
        }

        if($user->avatar){
            $oldAvatar = $user->avatar;
        }
        if($photo){
            if ($oldAvatar){
                Storage::disk('public')->delete($oldAvatar);
            }
            $path= $photo->store('uploads', 'public');
            $user->avatar = $path;
            $user->save();
        }
    }
}
