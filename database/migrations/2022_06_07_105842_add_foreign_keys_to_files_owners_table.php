<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToFilesOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files_owners', function (Blueprint $table) {
            $table->foreign('file_id')->references('id')->on('files');
            $table->foreign('owner_id')->references('id')->on('owners');
            $table->foreign('added_by_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files_owners', function (Blueprint $table) {
            $table->dropForeign('files_owners_file_id_foreign');
            $table->dropForeign('files_owners_owner_id_foreign');
            $table->dropForeign('files_owners_added_by_user_foreign');
        });
    }
}
