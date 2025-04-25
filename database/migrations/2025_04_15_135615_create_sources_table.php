<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('provider_ip')->nullable();
            $table->string('vmta')->nullable();
            $table->string('from');
            $table->string('return_path');
            $table->enum('spf', ['pass', 'fail', 'softfail', 'neutral', 'none', 'permerror', 'temperror']);
            $table->enum('dkim', ['pass', 'fail', 'none', 'permerror', 'temperror', 'policy']);
            $table->enum('dmarc', ['pass', 'fail', 'none', 'permerror', 'temperror', 'bestguesspass']);
            $table->timestamp('date');
            $table->string('email');
            $table->enum('message_path', ['inbox', 'spam']);
            $table->string('colonne')->nullable();
            $table->string('redirect_link')->nullable();
            $table->text('header');
            $table->longText('body');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sources');
    }
};