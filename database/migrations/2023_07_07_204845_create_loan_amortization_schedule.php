<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanAmortizationSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_amortization_schedule', function (Blueprint $table) {
            $table->id();
            $table->integer('month_number');
            $table->decimal('starting_balance', 2);
            $table->decimal('monthly_payment', 2);
            $table->decimal('prinicipal_component', 2);
            $table->decimal('interest_component', 2);
            $table->decimal('ending_balance', 2);
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
        Schema::dropIfExists('loan_amortization_schedule');
    }
}
