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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            /**
             * $table->foreignId('user_id')
             * создаёт поле user_id типа BIGINT UNSIGNED
             * внешняя связь (foreign key) — на пользователя
             *
             * ->constrained()
             * Laravel автоматически добавляет внешнюю связь на таблицу users(id)
             * сокращение $table->foreign('user_id')->references('id')->on('users');
             * Работает по имени поля — user_id → значит связь с users
             *
             * ->onDelete('cascade')
             * каскадное удаление
             * если user будет удалён, то все его товары удалятся автоматически
             * для комментариев - да, для товаров - не стоит.
             *
             */
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();

            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
