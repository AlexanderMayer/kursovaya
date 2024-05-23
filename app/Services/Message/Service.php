<?php

namespace App\Services\Message;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class Service{
    public function store(User $sender, User $recipient, $data, $photo){
        $path= null;
        $content= null;
        if($photo){
            $path= $photo->store('uploads', 'public');
        }
        if(isset($data['content'])){
            $content= $data['content'];
        }
        if(!$content && !$photo){
            return response()->json([
                'message'=>'At least one field must be filled in',
            ]);
        }
        Message::create([
            'sender'=>$sender->id,
            'recipient'=>$recipient->id,
            'content'=>$content,
            'photo'=>$path,
        ]);
    }

    public function update(){

    }
}
