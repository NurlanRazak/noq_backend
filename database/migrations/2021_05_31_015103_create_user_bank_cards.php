<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBankCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bank_cards', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->string('card_num');
            $table->string('card_name');

			$table->string('bank');
			$table->string('country');

			$table->string('token');
			$table->integer('year');
			$table->integer('month');

            $table->tinyInteger('enabled')->default(1);

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
        Schema::dropIfExists('user_bank_cards');
    }
}
