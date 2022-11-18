<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHakaksesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hak_akses', function (Blueprint $table) {
            $table->char('id', 10);
            $table->primary('id');
            $table->string('nama', 50);
            $table->string('username', 50);
            $table->text('password');
            $table->char('level', 25);
            $table->char('status', 2);
            $table->timestamps();
            $table->string('created_by', 25);
            $table->string('updated_by', 25);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hak_akses');
    }
}
