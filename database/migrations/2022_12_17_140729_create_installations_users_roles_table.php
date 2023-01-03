<?php
declare(strict_types=1);

use App\Entities\Installation;
use App\Entities\InstallationUserRole;
use App\Entities\User;
use App\Entities\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallationsUsersRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(InstallationUserRole::TABLE, function (Blueprint $table) {
            $table->unsignedInteger(InstallationUserRole::COLUMN_USER_ID);
            $table->unsignedInteger(InstallationUserRole::COLUMN_INSTALLATION_ID);
            $table->unsignedInteger(InstallationUserRole::COLUMN_USER_ROLE_ID);
            $table->timestamps();

            $table->foreign(InstallationUserRole::COLUMN_USER_ID)->references(User::COLUMN_ID)->on(User::TABLE);
            $table->foreign(InstallationUserRole::COLUMN_INSTALLATION_ID)->references(Installation::COLUMN_ID)->on(Installation::TABLE);
            $table->foreign(InstallationUserRole::COLUMN_USER_ROLE_ID)->references(UserRole::COLUMN_ID)->on(UserRole::TABLE);
            $table->primary([InstallationUserRole::COLUMN_USER_ID, InstallationUserRole::COLUMN_INSTALLATION_ID, InstallationUserRole::COLUMN_USER_ROLE_ID]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(InstallationUserRole::TABLE);
    }
}
