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
        Schema::create('tbl_fmea', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_jaring');
            $table->string('kategori_defect');
            $table->integer('severity')->default(1);
            $table->integer('occurrence')->default(1);
            $table->integer('detection')->default(1);
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
        Schema::dropIfExists('tbl_fmea');
    }
};
