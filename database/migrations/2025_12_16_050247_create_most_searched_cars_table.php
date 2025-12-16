<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('most_searched_cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('model');
            $table->decimal('price', 10, 2);
            $table->string('image');
            $table->integer('search_count')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('most_searched_cars');
    }
};