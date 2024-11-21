<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('appraisals'))
        {
            Schema::create(
                'appraisals', function (Blueprint $table){
                $table->bigIncrements('id');
                $table->integer('user_id');
                $table->integer('branch')->default(0);
                $table->integer('employee')->default(0);
                $table->string('rating')->nullable();
                $table->string('appraisal_date');
                $table->integer('customer_experience')->default(0);
                $table->integer('marketing')->default(0);
                $table->integer('administration')->default(0);
                $table->integer('professionalism')->default(0);
                $table->integer('integrity')->default(0);
                $table->integer('attendance')->default(0);
                $table->text('remark')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->timestamps();
            }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appraisals');
    }
};
