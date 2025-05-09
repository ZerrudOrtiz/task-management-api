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
            Schema::create('tasks', function (Blueprint $table) {
                $table->id('task_id');
                $table->string('title')->unique();
                $table->text('description')->nullable();
                $table->date('due_date')->nullable();
                $table->enum('status', ['done', 'in_progress', 'todo'])->default('todo');
                $table->enum('priority', ['low', 'medium', 'high'])->default('low');
                $table->unsignedBigInteger('user_id');
                $table->boolean('visible')->default(FALSE);
                $table->string('attachments')->nullable();
                $table->integer('order')->default(0);
                $table->unsignedBigInteger('created_by');
                $table->softDeletes();
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
