<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sisme.sub_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('permission_id'); // XD
            $table->foreign('permission_id')->references('id')->on('public.permissions');
            $table->string('icon');
            // $table->string('label');
            $table->string('route');
            $table->enum('type', ['Menu', 'SubMenu','Link'])->default('Menu');
            $table->integer('menu_id')->nullable(); // XD
            $table->foreign('menu_id')->references('id')->on('sisme.menus');
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
        Schema::dropIfExists('sisme.sub_menus');
    }
}
