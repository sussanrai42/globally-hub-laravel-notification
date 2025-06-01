<?php

use App\Enums\NotificationStatus;
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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type')->comment('sms, email, push etc.');
            $table->string('contact')->comment('email or phone or device token');
            $table->string('title')->comment("Title or subject of notification");
            $table->text('message')->comment("Main content/message");
            $table->json('payload')->nullable();
            $table->string('status')->default(NotificationStatus::PENDING->value);
            $table->text('error')->nullable();
            $table->timestamps();

            $table->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
