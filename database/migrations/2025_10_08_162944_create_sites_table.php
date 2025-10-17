<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('site_code')->unique();
            $table->string('site_name');
            $table->string('service_area');
            $table->string('sto');
            $table->enum('product', ['INTERSITE FO', 'MMP']);
            $table->string('tikor')->nullable();
            $table->string('teknisi')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tgl_visit')->nullable();
            $table->enum('progres', ['Sudah Visit', 'Belum Visit'])->default('Belum Visit');
            $table->string('operator')->nullable();
            $table->string('month_reference')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
