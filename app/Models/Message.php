<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'messages';
    protected $guarded=[];

    public function sender(){
        return $this->belongsTo(User::class, 'sender');
    }

    public function recipient(){
        return $this->belongsTo(User::class, 'recipient');
    }
}
