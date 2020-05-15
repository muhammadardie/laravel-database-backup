<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
			'name'        => 'Voldemort',
			'email'       => 'locolo@mail.com',
			'password'    => bcrypt('123456')
        ]);
    }
}
