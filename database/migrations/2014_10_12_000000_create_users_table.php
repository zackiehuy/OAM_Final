<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// @codingStandardsIgnoreLine
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('staff_code')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('location_id');
            $table->tinyInteger('gender')->default(1);
            $table->string('password');
            $table->tinyInteger('is_admin')->default(1);
            $table->date('date_of_birth');
            $table->date('joined_date');
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
