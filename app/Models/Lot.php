<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lot extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'lots';
    protected $guarded=[];

    public function seller(){
        return $this->belongsTo(User::class, 'seller');
    }
    public function buyer(){
        return $this->belongsTo(User::class, 'buyer');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function photos(){
        return $this->hasMany(Photo::class, 'lot_id');
    }
}
