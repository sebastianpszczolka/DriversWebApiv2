<?php
declare(strict_types=1);

use App\Entities\Group;
use App\Entities\User;
use App\Entities\UserGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(UserGroup::TABLE, function (Blueprint $table) {
            $table->unsignedInteger(UserGroup::COLUMN_USER_ID);
            $table->unsignedInteger(UserGroup::COLUMN_GROUP_ID);

            $table->foreign(UserGroup::COLUMN_USER_ID)->references(User::COLUMN_ID)->on(User::TABLE);
            $table->foreign(UserGroup::COLUMN_GROUP_ID)->references(Group::COLUMN_ID)->on(Group::TABLE);
            $table->primary([UserGroup::COLUMN_USER_ID, UserGroup::COLUMN_GROUP_ID]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(UserGroup::TABLE);
    }
}
