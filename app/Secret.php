<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
    protected $guarded = ["id"];

    public function user() {
        return $this->belongsTo("App\User");
    }

    public function spam() {
        return $this->hasMany("App\Spam");
    }

    public function comments() {
        return $this->hasMany("App\Comment");
    }
}
