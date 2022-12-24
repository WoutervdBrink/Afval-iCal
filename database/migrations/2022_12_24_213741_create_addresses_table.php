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
        Schema::create('addresses', function (Blueprint $table) {
            $table->foreignUuid('company_code')
                ->references('code')
                ->on('companies')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('id')->primary();
            $table->string('street');
            $table->string('house_number');
            $table->string('postal_code', 6);
            $table->string('city');
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
        Schema::dropIfExists('addresses');
    }
};
