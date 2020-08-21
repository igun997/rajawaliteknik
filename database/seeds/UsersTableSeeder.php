<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Direktur',
                'email' => 'direktur@rajawaliteknik.com',
                'username' => 'direktur',
                'password' => 'direktur',
                'level' => 0,
                'sub_level' => 0,
                'status' => 1,
                'created_at' => '2020-08-15',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Sekretaris',
                'email' => 'sekretaris@rajawaliteknik.com',
                'username' => 'sekretaris',
                'password' => 'sekretaris',
                'level' => 1,
                'sub_level' => 0,
                'status' => 1,
                'created_at' => '2020-08-15',
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Bendahara',
                'email' => 'bendahara@rajawaliteknik.com',
                'username' => 'bendahara',
                'password' => 'bendahara',
                'level' => 2,
                'sub_level' => 0,
                'status' => 1,
                'created_at' => '2020-08-15',
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Indra',
                'email' => 'indra.gunanda@gmail.com',
                'username' => 'indra',
                'password' => 'indra',
                'level' => 0,
                'sub_level' => 1,
                'status' => 1,
                'created_at' => '2020-08-16',
                'updated_at' => '2020-08-16',
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'Subakun Bendahara',
                'email' => 'bendahara2@rajawaliteknik.com',
                'username' => 'bendahara2',
                'password' => 'bendahara2',
                'level' => 2,
                'sub_level' => 1,
                'status' => 1,
                'created_at' => '2020-08-16',
                'updated_at' => '2020-08-16',
            ),
            5 => 
            array (
                'id' => 7,
                'name' => 'Sekretaris2',
                'email' => 'sekretaris2@rajawaliteknik.com',
                'username' => 'sekretaris2',
                'password' => 'sekretaris2',
                'level' => 1,
                'sub_level' => 1,
                'status' => 1,
                'created_at' => '2020-08-16',
                'updated_at' => '2020-08-16',
            ),
        ));
        
        
    }
}