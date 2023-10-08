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
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string("title", 255)->nullable();
            $table->text("description")->nullable();
            $table->boolean("is_completed")->default(false);
            $table->timestamp('due_date')->default(now());

            $table->timestamps();
            $table->softDeletes();

            // Связь один-ко-многим с таблицей users
            $table->foreign("user_id") // Определяем внешний ключ
            ->references("id")
                ->on("users")
                ->onDelete("cascade"); // При удалении пользователя, удаляем связанные с ним заметки
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
