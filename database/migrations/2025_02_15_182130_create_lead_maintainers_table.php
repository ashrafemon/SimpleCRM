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
        Schema::create('lead_maintainers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('lead_id');
            $table->string('user_id');
            $table->enum('status', ['ASSIGNED', 'IN_PROGRESS', 'BAD_TIMING', 'NOT_INTERESTED', 'NOT_QUALIFIED', 'CONVERTED'])->default('ASSIGNED');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_maintainers');
    }
};
