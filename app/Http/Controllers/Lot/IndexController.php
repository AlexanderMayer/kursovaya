<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;

class IndexController extends BaseController
{//отображение лотов пользователя + фильтрация
    public function __invoke(Request $request){
        $cat_id= $request->input('category_id', null);
        $status_id= $request->input('status_id', null);
        $user = auth()->user();

        $user_lots= $user->sold();

        // Получение лотов по статусу
        // Получение лотов по категории
        // Получение всех лотов
        // Получение лотов по статусу и категории

        if($cat_id && $status_id){
            $filtred= $user_lots->where('category_id', $cat_id)->where('status', $status_id)->get();
        }else if($cat_id){
            $filtred= $user_lots->where('category_id', $cat_id)->get();
        }else if($status_id){
            $filtred= $user_lots->where('status', $status_id)->get();
        }else{
            $filtred= $user_lots->get();
        }

        $filtred = $filtred->map(function ($item) {
            $photos = Photo::where('lot_id', $item->id)->get();
            $images = $photos->map(function($photo) {
                return [
                    'id' => $photo->id,
                    'adress' => $photo->adress
                ];
            })->toArray();

            $item->images = $images;

            return $item;
        });

        return $filtred;

    }

//    public function __invoke(){
//        $user = auth()->user();
//        $data = $user->sold()->get();
//
//        $data = $data->map(function ($item) {
//            $photos = Photo::where('lot_id', $item->id)->get();
//            $images = $photos->map(function($photo) {
//                return [
//                    'id' => $photo->id,
//                    'adress' => $photo->adress
//                ];
//            })->toArray();
//
//            $item->images = $images;
//
//            return $item;
//        });
//
//        return $data;
//    }
}
