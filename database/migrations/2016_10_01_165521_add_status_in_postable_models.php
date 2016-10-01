<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusInPostableModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('status')->nullable()->after('picture');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->string('status')->nullable()->after('picture');
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->string('status')->nullable()->after('picture');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('status')->nullable()->after('picture');
        });

        Schema::table('static_pages', function (Blueprint $table) {
            $table->string('status')->nullable()->after('picture');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->string('status')->nullable()->after('picture');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('static_pages', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
