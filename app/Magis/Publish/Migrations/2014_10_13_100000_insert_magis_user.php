<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertMagisUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        User::create([
            'photo' => 'https://cdn.magis.solutions/magissolutions-inc/users/1/logo_promo2.PNG',
            'name' => 'MagisSolutions',
            'email' => 'changelives@magis.solutions',
            'password' => bcrypt(env('DEFAULT_MAGIS_PASSWORD')),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('users')->whereId(1)->delete();
    }
}
