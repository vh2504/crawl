<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWooproductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wooproducts', function (Blueprint $table) {
            $table->id();
            $table->String('ProductName', 1000)->nullable();
            $table->text('Description')->nullable();
            $table->text('ShortDescription')->nullable();
            $table->float('RegularPrice')->nullable();
            $table->float('SalePrice')->nullable();
            $table->String('Image', 5000)->nullable();
            $table->String('Style', 500)->nullable();
            $table->String('Color', 500)->nullable();
            $table->String('Size', 500)->nullable();
            $table->text('Tags')->nullable();
            $table->text('ProductCode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wooproducts');
    }
}
