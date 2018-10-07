<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = ["id"];

    public function secret() {
        return $this->belongsTo("App\Secret");
    }
    public function user() {
        return $this->belongsTo("App\User");
    }
}
