<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccessRolesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_roles', function (Blueprint $table) {
            $table->increments('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->string('title');
            $table->string('description')->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->text('permission')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('access_roles');
    }

}
