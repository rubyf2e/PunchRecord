<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$users =
    	array(
    		array(
    			'name'       => '皮卡丘1號',
    			'permission' => 'Admin',
    			'member_no'  => 'AA0001',
    			'email'      => str_random(10).'@gmail.com',
    			'password'   => bcrypt('secret'),
    			'name'       => '皮卡丘1號',
    		),
    		array(
    			'name'       => '皮卡丘2號',
    			'permission' => 'Member',
    			'member_no'  => 'AA0002',
    			'email'      => str_random(10).'@gmail.com',
    			'password'   => bcrypt('secret'),
    		),
    		array(
    			'name'       => '皮卡丘3號',
    			'permission' => 'Member',
    			'member_no'  => 'AA0003',
    			'email'      => str_random(10).'@gmail.com',
    			'password'   => bcrypt('secret'),
    		)
    	);

    	DB::table('users')->insert($users);
    }
  }
