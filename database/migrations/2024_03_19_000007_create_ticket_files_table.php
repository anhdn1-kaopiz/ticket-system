<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')
                  ->constrained('tickets')
                  ->onDelete('cascade');

            $table->string('filename');
            $table->string('filepath');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_files');
    }
};
