<?php

use App\Entities\User;
use App\Entities\UserCfg;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersCfgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(UserCfg::TABLE, function (Blueprint $table) {
            $table->increments(UserCfg::COLUMN_ID);
            $table->unsignedInteger(UserCfg::COLUMN_USER_ID)->unique();
            $table->text(UserCfg::COLUMN_CFG);
            $table->timestamps();

            $table->foreign(UserCfg::COLUMN_USER_ID)->references(User::COLUMN_ID)->on(User::TABLE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(UserCfg::TABLE);
    }
}
