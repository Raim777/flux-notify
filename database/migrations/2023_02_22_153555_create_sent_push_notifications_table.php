<?php

use App\Helpers\NotificationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_push_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('token_id')->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('status')->default(NotificationHelper::STATUS_UNREAD);
            $table->morphs('pushable');
            $table->foreignId('notification_type_id')->nullable()->references('id')->on('notification_types')->cascadeOnDelete();
            $table->text('fields_json')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sent_push_notifications');
    }
};
