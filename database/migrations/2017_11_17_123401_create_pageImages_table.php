<?php

use App\PageImage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pageImages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('page_id');
            $table->unsignedSmallInteger('sort')->default(0);
            $table->unsignedTinyInteger('enabled');
            $table->string('filename')->nullable();
            $table->text('fileInfo')->nullable();
            $table->timestamps();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //delete images
        PageImage::all(PageImage::FILE_ATTRIBUTE_NAME)->each(function($item){
            PageImage::deleteFileFromServer($item->fileServerPath());
        });
        Schema::dropIfExists('pageImages');
    }
}
