<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request){
//        $data= $request->validated();
//        $path = $request->file('avatar')->store('uploads', 'public');
//        $user = $this->service->store($data);
//        $user->avatar = $path;
//        dd($path);

        $data = $request->validated();
        $path = $request->file('avatar')->store('uploads', 'public');
        $user = $this->service->store($data);

        $relativePath = 'uploads/' . basename($path);

        $user->avatar = $relativePath;
        $user->save();

        // Добавляйте базовую директорию хранилища при отображении файла на веб-странице

        dd($relativePath);
    }
}

//$path = $request->file('image')->store('uploads', 'public');
//auth()->user()->avatar = $path;
