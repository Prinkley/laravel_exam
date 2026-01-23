<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archived_things', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thing_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamp('warranty')->nullable();
            $table->string('master_name');
            $table->foreignId('master_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('last_user_name')->nullable();
            $table->foreignId('last_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('place_name')->nullable();
            $table->foreignId('place_id')->nullable()->constrained('places')->onDelete('set null');
            $table->boolean('is_restored')->default(false);
            $table->foreignId('restored_by_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('restored_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archived_things');
    }
};
