<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menuItems', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('menu_id')->index();
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->unsignedSmallInteger('type');
            $table->string('content')->nullable()->index();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('menuItems')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menuItems');
    }
}
