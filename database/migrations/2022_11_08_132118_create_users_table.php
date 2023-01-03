<?php
declare(strict_types=1);

use App\Entities\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(User::TABLE, function (Blueprint $table) {
            $table->increments(User::COLUMN_ID);
            $table->string(User::COLUMN_PASSWORD, 255);
            $table->string(User::COLUMN_EMAIL, 255);
            $table->boolean(User::COLUMN_ACTIVATED)->default(false);
            $table->string(User::COLUMN_ACTIVATION_CODE, 255)->nullable();
            $table->timestamp(User::COLUMN_ACTIVATED_AT)->nullable();
            $table->string(User::COLUMN_PHONE, 14)->nullable();
            $table->string(User::COLUMN_FIRST_NAME, 255)->nullable();
            $table->string(User::COLUMN_LAST_NAME, 255)->nullable();
            $table->timestamp(User::COLUMN_LAST_LOGIN)->nullable();
            $table->string(User::COLUMN_RESET_PASSWORD_CODE, 255)->nullable();
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
        Schema::dropIfExists(User::TABLE);
    }
}
