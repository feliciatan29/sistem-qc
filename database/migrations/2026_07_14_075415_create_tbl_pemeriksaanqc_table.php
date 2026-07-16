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
        Schema::create('tbl_pemeriksaanqc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produksi');
            $table->string('jenis_jaring')->nullable();
            $table->string('bulan_produksi')->nullable();
            $table->string('jumlah_pesanan')->nullable();
            $table->integer('jumlah_cek')->default(0);
            $table->integer('baik')->default(0);
            $table->integer('rr')->default(0);
            $table->integer('pr')->default(0);
            $table->integer('rps')->default(0);
            $table->integer('super')->default(0);
            $table->integer('rj')->default(0);
            $table->integer('berbulu')->default(0);
            $table->integer('rusak_blok')->default(0);
            $table->integer('total_defect')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_produksi')->references('id')->on('tbl_dataproduksi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pemeriksaanqc');
    }
};
