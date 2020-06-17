
<?php

use App\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->unsignedSmallInteger('type')->index()->default(Page::TYPE_PAGE);
            $table->unsignedSmallInteger('sortType')->nullable();
            $table->string('customView')->nullable();
            $table->unsignedSmallInteger('enabled')->default(Page::ENABLED_YES);
            $table->string('slug')->unique()->nullable();
            $table->string('image')->nullable();
            $table->string('fileInfo')->nullable();
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
            $table->foreign('parent_id')->references('id')->on('pages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
