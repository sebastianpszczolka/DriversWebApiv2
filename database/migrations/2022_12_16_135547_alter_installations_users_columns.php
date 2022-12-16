<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInstallationsUsersColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('installations_users', function (Blueprint $table) {
            $table->addColumn('integer', 'user_role_id', ['unsigned' => true])->nullable();
            $table->foreign('user_role_id')->references('id')->on('users_roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('installations_users', function (Blueprint $table) {
            $table->dropColumn('user_role_id');
        });

    }
}
