<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        
        if(env('APP_ENV') == 'local'){
            $this->deleteAll();
            factory(User::class, 'AdminUser', 1)->create();
            factory(User::class, 'ManagerUser', 1)->create();
        }
    }
    
    /** Delete all Users
     * @return void */
    public function deleteAll()
    {
        try{
            User::all()->each(function($model){
                $model->delete();
            });
        } catch(Exception $e){
            echo 'SOMETHING WENT WRONG ON USERS DELETION';
        }
        DB::table( (new User())->getTable() )->truncate();
    }
}
