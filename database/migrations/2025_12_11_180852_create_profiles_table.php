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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId( 'user_id' )->constrained()->onDelete('cascade'); // it'll automatically select the id column of users amd it's modern way of writing foreign key
            $table->string( 'phone' )->nullable();
            $table->string( 'address' )->nullable();
            $table->string( 'avatar' )->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
