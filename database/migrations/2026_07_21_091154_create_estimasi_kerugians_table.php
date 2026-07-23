<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimasiKerugiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimasi_kerugians', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_jaring');
            $table->double('target')->default(0);
            $table->double('aktual')->default(0);
            $table->double('produksi_hari')->default(0);
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
        Schema::dropIfExists('estimasi_kerugians');
    }
}
