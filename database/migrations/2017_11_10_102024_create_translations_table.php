<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('translationable');
            $table->string('lang', 15)->index();
            $table->unsignedSmallInteger('column')->index();
            $table->longText('value')->nullable(true);
            $table->unique([
                'translationable_type',
                'translationable_id',
                'lang',
                'column'
            ], 'translationable_type_id_lang_column');
            $table->foreign('lang')->references('code')->on('languages')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('translations');
    }
}
