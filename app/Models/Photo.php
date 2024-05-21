<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $table = 'photos';
    protected $guarded=[];
    public $timestamps = false;


    public function lot(){
        return $this->belongsTo(Lot::class, 'lot_id');
    }
}
