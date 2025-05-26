<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->timestamps();
        });

        //orderm alfabetica e no singular nome da table permission_user
        Schema::create('permission_user', function (Blueprint $table) {
            $table->foreignId('user_id');
            $table->foreignId('permission_id');
            // index serve para facilitar a busca e melhorar a performance
            $table->index(['user_id', 'permission_id']);
            $table->unique(['user_id', 'permission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('permission_user');
    }
};
