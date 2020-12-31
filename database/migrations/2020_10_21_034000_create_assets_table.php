<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// @codingStandardsIgnoreLine
class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset', function (Blueprint $table) {
            $table->increments('id');
            $table->string('asset_code', 50)->nullable();
            $table->integer('location_id');
            $table->integer('asset_category_id')->unsigned();
            $table->string('description', 500)->nullable();
            $table->date('installed_date');
            $table->boolean('is_delete')->default(false);
            $table->string('name', 50)->nullable();
            $table->string('create_by', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset');
    }
}
