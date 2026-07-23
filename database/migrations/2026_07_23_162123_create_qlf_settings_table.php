<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQlfSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qlf_settings', function (Blueprint $table) {
            $table->id();
            $table->double('biaya_kerugian')->default(50000);
            $table->double('batas_toleransi')->default(100);
            $table->double('hari_produksi')->default(30);
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
        Schema::dropIfExists('qlf_settings');
    }
}
