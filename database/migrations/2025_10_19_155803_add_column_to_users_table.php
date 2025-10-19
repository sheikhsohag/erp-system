<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('users', function(Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('name');
            $table->string('role')->default('employee')->after('email');
            $table->boolean('is_on_leave')->default(false)->after('role');

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }
    public function down() {
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id','role','is_on_leave']);
        });
    }
};
