<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('requests', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('status')->default('pending'); // pending/approved/rejected
            $table->unsignedBigInteger('current_approver_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('current_approver_id')->references('id')->on('users')->onDelete('set null');
        });
    }
    public function down() { Schema::dropIfExists('requests'); }
};
