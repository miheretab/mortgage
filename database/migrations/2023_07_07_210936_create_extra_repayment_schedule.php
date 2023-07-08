<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraRepaymentSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_repayment_schedule', function (Blueprint $table) {
            $table->id();
            $table->integer('month_number');
            $table->decimal('starting_balance', 2);
            $table->decimal('monthly_payment', 2);
            $table->decimal('prinicipal_component', 2);
            $table->decimal('interest_component', 2);
            $table->decimal('extra_repayment', 2);
            $table->decimal('ending_balance', 2);
            $table->integer('remaining_loan_term');
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
        Schema::dropIfExists('extra_repayment_schedule');
    }
}
