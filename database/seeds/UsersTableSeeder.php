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
    {//id	comment_id	users_id	date	like	dislike	created_at	updated_at


        for ($i=1808; $i <=1908; $i++) {
        DB::table('commentlikes')->insert([
            'comment_id' =>$i,
            'users_id' =>rand(1,100),
            'like' =>rand(1,10),
            'dislike' =>rand(1,10),

        ]);
    }
    }
}
