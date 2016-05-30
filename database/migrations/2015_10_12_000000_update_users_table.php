<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->integer('site_id')->unsigned()->nullable()->after('id');
            $table->string('username')->after('email');
            $table->string('avatar')->nullable()->after('password');
            $table->string('background')->nullable()->after('avatar');
            $table->string('jobtitle')->nullable()->after('background');
            $table->text('socials')->nullable()->after('jobtitle');
            $table->text('about')->nullable()->after('socials');
            
            $table->foreign('site_id')
                  ->references('id')->on('sites')
                  ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
            $table->dropColumn('username');
            $table->dropColumn('avatar');
            $table->dropColumn('background');
            $table->dropColumn('jobtitle');
            $table->dropColumn('socials');
            $table->dropColumn('about');
        });
    }
}
