<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable , SoftDeletes;

    protected $table= 'users';
    protected $guarded= [];

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function sold(){
        return $this->hasMany(Lot::class, 'seller');
    }

    public function bought(){
        return $this->hasMany(Lot::class, 'buyer');
    }

    public function sentMsg(){
        return $this->hasMany(Message::class, 'sender');
    }

    public function recMsg(){
        return $this->hasMany(Message::class, 'recipient');
    }

    public function writtenComplaints(){
        return $this->hasMany(Complaint::class, 'author');
    }

    public function receivedComplaints(){
        return $this->hasMany(Complaint::class, 'target');
    }


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
