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
            $table->string('ip')->index(); // Added index for faster searching
            $table->string('provider_ip')->nullable();
            $table->string('vmta')->nullable();
            $table->string('from');
            $table->string('return_path');

            // SPF: Sender Policy Framework results
            $table->enum('spf', [
                'pass',       // SPF check passed
                'fail',        // SPF check failed
                'softfail',    // SPF soft fail
                'neutral',     // SPF neutral
                'none',        // No SPF record
                'permerror',   // Permanent error
                'temperror'    // Temporary error
            ]);

            // DKIM: DomainKeys Identified Mail results
            $table->enum('dkim', [
                'pass',       // DKIM check passed
                'fail',       // DKIM check failed
                'none',       // No DKIM record
                'permerror', // Permanent error
                'temperror', // Temporary error
                'policy'     // Policy related failure
            ]);

            // DMARC: Domain-based Message Authentication results
            $table->enum('dmarc', [
                'pass',         // DMARC check passed
                'fail',          // DMARC check failed
                'none',         // No DMARC record
                'permerror',     // Permanent error
                'temperror',     // Temporary error
                'bestguesspass'  // Best guess pass
            ]);

            $table->datetime('date')->index(); // Added index for faster sorting
            $table->string('email');
            $table->enum('message_path', ['inbox', 'spam']);
            $table->string('colonne')->nullable();
            $table->string('redirect_link')->nullable();
            $table->mediumText('header');
            $table->longText('body');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sources');
    }
};
