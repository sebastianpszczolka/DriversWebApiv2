<?php

use App\Entities\Obj;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Obj::TABLE, function (Blueprint $table) {
            $table->increments(Obj::COLUMN_ID);
            $table->text(Obj::COLUMN_KEY)->unique();
            $table->text(Obj::COLUMN_VALUE);

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
        Schema::dropIfExists(Obj::TABLE);
    }
}
