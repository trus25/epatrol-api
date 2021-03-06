<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_report')->unsigned();
            $table->bigInteger('id_report_detail')->unsigned()->nullable();
            $table->bigInteger('id_respondent')->unsigned();
            $table->string('message')->nullable();
            $table->enum('report_type',['R','S'])->default('R'); // R = Reports, S = SOS
            $table->enum('sos_to',['Owner','Client'])->nullable();
            $table->enum('message_type',['Video','Text','Audio','Image'])->default('Text');
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
