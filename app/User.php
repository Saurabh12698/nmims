<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens ,  Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',  'mobile' , 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function clearTokens()
    {
        foreach ($this->tokens as $token) {
            if ($token->user_id == $this->id) {
                $token->revoke();
                $token->delete();
            }
        }
        return;
    }
    

    public function secrets() {
        return $this->hasMany("App\Secret");
    }

    public function comments() {
        return $this->hasMany("App\Comment");
    }

    public function spams() {
        return $this->hasMany("App\Spam");
    }
}
