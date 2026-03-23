<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('nickname', 100)->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date')->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->text('current_address')->nullable();
            $table->string('occupation')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('photo_path')->nullable();
            $table->unsignedBigInteger('father_id')->nullable();
            $table->unsignedBigInteger('mother_id')->nullable();
            $table->integer('generation')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('father_id')->references('id')->on('members')->onDelete('set null');
            $table->foreign('mother_id')->references('id')->on('members')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
