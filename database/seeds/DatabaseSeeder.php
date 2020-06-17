<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Schema::disableForeignKeyConstraints();
        
        //run seeders for tables with static data
//        $this->call(DatabaseDataSeeder::class);
        
        //run seeders for tables with updatable data
        if(env('APP_ENV') == 'local' || env('APP_ENV') == 'production'){
            $this->call(LanguagesTableSeeder::class);
            $this->call(UsersTableSeeder::class);
            $this->call(PagesTableSeeder::class);
            $this->call(SettingsTableSeeder::class);
        }

        Model::reguard();
        Schema::enableForeignKeyConstraints();
    }
}
