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
        Schema::create('agent_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('state');
            $table->dateTime('date_start');
            $table->dateTime('date_end')->nullable();
            $table->string('shift');
            $table->time('office_start');
            $table->time('office_end');
            $table->integer('duration_mins')->nullable();
            $table->timestamp('processed_at')->nullable();

            $table->index(['user_id', 'date_start']);
            $table->index(['shift']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shift')->references('kode_ihc')->on('roster_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_log');
    }
};
