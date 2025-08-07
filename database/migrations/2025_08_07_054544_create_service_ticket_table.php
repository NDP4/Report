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
        Schema::create('service_ticket', function (Blueprint $table) {
            $table->id('ticket_id');
            $table->unsignedBigInteger('user_id');
            $table->string('subject');
            $table->text('description');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('priority_id');
            $table->dateTime('date_open');
            $table->dateTime('date_close')->nullable();
            $table->integer('sla_minutes');
            $table->integer('time_to_resolve')->nullable();
            $table->tinyInteger('sla_met')->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('ticket_category');
            $table->foreign('subcategory_id')->references('id')->on('ticket_subcategory');
            $table->foreign('status_id')->references('id')->on('ticket_status');
            $table->foreign('source_id')->references('id')->on('ticket_source');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('priority_id')->references('id')->on('priority_level');

            // Indexes
            $table->index(['user_id', 'date_open']);
            $table->index(['status_id']);
            $table->index(['assigned_to']);
            $table->index(['date_open']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_ticket');
    }
};
