<?php

use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('task_statuses')->truncate();

        DB::table('task_statuses')->insert([
            ['name' => 'Pending', 'color' => '#f5ac2f'],
            ['name' => 'Completed', 'color' => '#1adb7e'],
            ['name' => 'Needs Changes', 'color' => '#1a57db'],
            ['name' => 'Failed', 'color' => '#db1a3d']
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
