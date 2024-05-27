<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded=[];
    protected $table= 'complaints';

    public function author(){
        return $this->belongsTo(User::class, 'author');
    }

    public function target(){
        return $this->belongsTo(User::class, 'target');
    }
}
