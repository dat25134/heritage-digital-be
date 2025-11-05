<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topic_media_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('topic_id');
            $table->unsignedBigInteger('media_id');
            $table->unsignedInteger('sort_order')->default(0);

            $table->primary(['topic_id', 'media_id']);
            $table->index(['topic_id', 'sort_order']);

            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
            // media table belongs to spatie/laravel-medialibrary
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topic_media_orders');
    }
};


