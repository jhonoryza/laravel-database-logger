<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('log_apis', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable();
            $table->string('method')->nullable();
            $table->string('code')->nullable();
            $table->longText('header')->nullable();
            $table->longText('payload')->nullable();
            $table->longText('response')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_apis');
    }
};
