<?php
declare(strict_types=1);

use App\Entities\Group;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Group::TABLE, function (Blueprint $table) {
            $table->increments(Group::COLUMN_ID);
            $table->string(Group::COLUMN_NAME)->unique();
            $table->text(Group::COLUMN_PERMISSIONS)->nullable();
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
        Schema::dropIfExists(Group::TABLE);
    }
}
