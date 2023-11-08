<?php

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
        Schema::create('quick_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->text('subject');
            $table->text('text');
            $table->boolean('send_sms')->default(false);
            $table->boolean('to_all')->default(false);
            $table->text('cities')->nullable();
            $table->foreignId('notification_type_id')->nullable()->references('id')
                ->on('notification_types')->nullOnDelete();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('quick_notifications_types');
    }
};
