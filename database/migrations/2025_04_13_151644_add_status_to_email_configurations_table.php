<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToEmailConfigurationsTable extends Migration
{
    public function up()
    {
        Schema::table('email_configurations', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->after('mail_from_address');
        });
    }

    public function down()
    {
        Schema::table('email_configurations', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
