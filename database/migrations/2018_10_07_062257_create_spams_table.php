<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spams', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedInteger("secret_id");
            $table->foreign("secret_id")->references("id")->on("secrets");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spams');
    }
}
