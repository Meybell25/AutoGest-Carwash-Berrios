<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    if (!Schema::hasColumn('citas', 'updated_at')) {
        Schema::table('citas', function (Blueprint $table) {
            $table->timestamp('updated_at')->nullable()->after('created_at');
        });
    }
}

public function down()
{
    Schema::table('citas', function (Blueprint $table) {
        $table->dropColumn('updated_at');
    });
}
};
