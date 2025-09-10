<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('log_apps', function (Blueprint $table) {
            $table->id();
            $table->longText('message');
            $table->json('context')->nullable();
            $table->string('level')->index();
            $table->string('level_name');
            $table->string('channel')->index();
            $table->dateTimeTz('record_datetime')->nullable();
            $table->longText('extra')->nullable();
            $table->longText('formatted');
            $table->string('remote_addr')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_apps');
    }
};
