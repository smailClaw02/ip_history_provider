<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('source', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('provider_ip');
            $table->string('vmta');
            $table->string('from');
            $table->string('return_path');
            $table->enum('spf', ['pass', 'fail', 'softfail', 'neutral', 'none', 'permerror', 'temperror']);
            $table->enum('dkim', ['pass', 'fail', 'none', 'permerror', 'temperror', 'policy']);
            $table->enum('dmark', ['pass', 'fail', 'none', 'permerror', 'temperror']);
            $table->timestamp('date');
            $table->string('email');
            $table->string('message_path');
            $table->string('colonne');
            $table->string('redirect_link');
            $table->text('header');
            $table->text('body');
            $table->json('domains')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('source');
    }
};