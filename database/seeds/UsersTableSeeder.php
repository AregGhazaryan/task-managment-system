<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();

        DB::table('users')->insert([
            [
                'name' => 'TCO LLC',
                'email' => 'tco@llc.com',
                'password' => Hash::make('password')
            ],
            [
                'name' => 'Alexa',
                'email' => 'alexa@garcia.com',
                'password' => Hash::make('password')
            ],
            [
                'name' => 'John',
                'email' => 'john@doe.com',
                'password' => Hash::make('password')
            ],
            [
                'name' => 'Dean',
                'email' => 'dean@winchester.com',
                'password' => Hash::make('password')
            ],
            [
                'name' => 'Chris',
                'email' => 'chris@cornell.com',
                'password' => Hash::make('password')
            ],
            [
                'name' => 'Haley',
                'email' => 'haley@williams.com',
                'password' => Hash::make('password')
            ],
            [
                'name' => 'Todd',
                'email' => 'todd@howard.com',
                'password' => Hash::make('password')
            ]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
