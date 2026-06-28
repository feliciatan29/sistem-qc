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
        Schema::create('tbl_pengaturan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_mesin');
            $table->string('jenis_jaring');
            $table->string('ukuran_jaring');
            $table->string('MD_jaring');
            $table->string('RPM_jaring');
            $table->string('status');
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
        Schema::dropIfExists('tbl_pengaturan');
    }
};
