<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->text('description');
            $table->string('image')->nullable(); // optional task image
            $table->enum('response_type', ['text', 'file']); // expected user response type
            $table->timestamp('expires_at'); // expiration deadline
            $table->decimal('price', 10, 2)->default(0);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // admin who created
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
