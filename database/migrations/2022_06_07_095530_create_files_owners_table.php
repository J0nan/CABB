<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_owners', function (Blueprint $table) {
            $table->bigInteger('file_id')->unsigned();
            $table->string('owner_id');
            $table->bigInteger('added_by_user')->nullable(false)->unsigned();;
            $table->timestamps();
            $table->softDeletes();
            $table->primary(['file_id', 'owner_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files_owners');
    }
}
