<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//	id	User_id	national_id	created_at	updated_at

        for ($i=0; $i < 24; $i++) {
            DB::table('admin')->insert([
                'User_id'=>rand(1,25),
                'national_id' =>str_random(10),
            ]);
        }
    }
}
