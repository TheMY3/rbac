<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionPermissionGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_permission_group', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('permission_group_id')->unsigned();
            
            $table->foreign('permission_id', 'ppg_permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('permission_group_id', 'ppg_permission_group_id')
                ->references('id')
                ->on('permission_groups')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'permission_group_id'], 'ppg_permission_id_group_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_permission_group');
    }
}
