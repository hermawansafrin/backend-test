<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->index();
            $table->integer('customer_id')->unsigned();
            $table->tinyInteger('status_flow_id')->unsigned();

            $table->integer('total_amount')->unsigned()->default(0);
            $table->integer('total_without_discount')->unsigned()->default(0);
            $table->integer('total_discount')->unsigned()->default(0);
            $table->tinyInteger('discount_percentage')->unsigned()->default(0);

            $table->dateTime('paid_date_time')->nullable();
            $table->tinyText('note')->nullable();

            $table->integer('created_user_id')->unsigned();
            $table->integer('last_updated_user_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
