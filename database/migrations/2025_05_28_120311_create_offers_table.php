<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('id_offer');
            $table->string('category');
            $table->string('name');
            $table->decimal('rev', 10, 2)->default(0.00);
            $table->string('img')->default('not found');
            $table->string('link_img')->default('not found');
            $table->string('link_uns')->default('not found');
            $table->unsignedInteger('count_lead')->default(0);
            $table->string('from')->default('not found');
            $table->string('sub')->default('not found');
            $table->timestamps();
            
            $table->index('id_offer');
        });
    }

    public function down()
    {
        Schema::dropIfExists('offers');
    }
};