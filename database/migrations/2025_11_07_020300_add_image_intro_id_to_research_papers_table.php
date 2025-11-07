<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('research_papers', function (Blueprint $table): void {
            $table->unsignedBigInteger('image_intro_id')->nullable()->after('id')->index();
            $table->foreign('image_intro_id')
                ->references('id')
                ->on('image_intros')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('research_papers', function (Blueprint $table): void {
            $table->dropForeign(['image_intro_id']);
            $table->dropColumn('image_intro_id');
        });
    }
};


