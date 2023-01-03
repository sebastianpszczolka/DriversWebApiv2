<?php
declare(strict_types=1);

use App\Entities\Installation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Installation::TABLE, function (Blueprint $table) {
            $table->increments(Installation::COLUMN_ID);
            $table->string(Installation::COLUMN_NAME, 100);
            $table->string(Installation::COLUMN_DESCRIPTION, 255)->nullable();
            $table->unsignedBigInteger(Installation::COLUMN_INSTALLATION_BARCODE)->unique();
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
        Schema::dropIfExists(Installation::TABLE);
    }
}
