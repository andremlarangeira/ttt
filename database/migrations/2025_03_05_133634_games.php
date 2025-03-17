<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->tinyInteger('is_active')->default(1);
            $table->string('current_player', 1)->nullable();
            $table->bigInteger('player_x_id', false, true)->nullable();
            $table->bigInteger('player_o_id', false, true)->nullable();
            $table->string('board')->nullable();
            $table->string('moves_x')->nullable();
            $table->string('moves_o')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
