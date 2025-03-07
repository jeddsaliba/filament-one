<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Jeddsaliba\FilamentMessenger\Models\Inbox;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fm_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Inbox::class);
            $table->text('message')->nullable();
            $table->foreignIdFor(\App\Models\User::class);
            $table->json('read_by')->nullable();
            $table->json('read_at')->nullable();
            $table->json('notified')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fm_messages');
    }
};