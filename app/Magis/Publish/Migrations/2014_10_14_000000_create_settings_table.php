<?php

use App\Magis\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            $table->text('content');
            $table->integer('created_by')->unsigned()->default(0);
            $table->integer('updated_by')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Setting::insert([
            ['slug' => 'site-title', 'name' => 'Site Title', 'content' => 'MagisSolutions'],
            ['slug' => 'site-tagline', 'name' => 'Site Tagline', 'content' => 'Software for a Better World'],
            ['slug' => 'site-description', 'name' => 'Site Description', 'content' => 'MagisSolutions is a cause-oriented software development company. It provides radically discounted services to NGOs, Foundations, social enterprises, and other cause-oriented organizations.'],
            ['slug' => 'facebook', 'name' => 'Facebook', 'content' => 'https://www.facebook.com/MagisSolutions'],
            ['slug' => 'twitter', 'name' => 'Twitter', 'content' => 'https://twitter.com/magisians'],
            ['slug' => 'linkedin', 'name' => 'LinkedIn', 'content' => ''],
            ['slug' => 'contact-e-mail', 'name' => 'Contact E-mail', 'content' => 'changelives@magis.solutions'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('settings');
    }
}
