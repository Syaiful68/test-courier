<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->string('courier_id')->unique();
            $table->string('courier_nik')->unique();
            $table->string('courier_name');
            $table->string('courier_phone');
            $table->string('courier_zone');
            $table->boolean('courier_status')->default(true);
            $table->enum('courier_roles',['courier','operator','manager','super-admin','developer'])->default('courier');
            $table->softDeletes('deleted_at')->nullable();
            $table->bigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couriers');
    }
};
