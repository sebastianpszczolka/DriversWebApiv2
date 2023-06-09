<?php
declare(strict_types=1);

use App\Entities\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(UserRole::TABLE, function (Blueprint $table) {
            $table->increments(UserRole::COLUMN_ID);
            $table->string(UserRole::COLUMN_ROLE, 32)->unique();
            $table->string(UserRole::COLUMN_DESCRIPTION, 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(UserRole::TABLE);
    }
}
