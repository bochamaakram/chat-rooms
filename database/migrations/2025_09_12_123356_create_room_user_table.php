<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomUserTable extends Migration
{
    public function up()
    {
        Schema::create('room_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['room_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('room_user');
    }
}