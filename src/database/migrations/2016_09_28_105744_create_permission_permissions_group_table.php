<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionPermissionsGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_permissions_group', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('permissions_group_id')->unsigned();
            
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('permissions_group_id')
                ->references('id')
                ->on('permissions_groups')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'permissions_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_permissions_group');
    }
}
