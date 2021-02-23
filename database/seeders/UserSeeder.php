<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\Organisation;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use DB;
use Str;
use Database\Factories\UserFactory;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $userFactory = new UserFactory();
        DB::table('users')->insert(

            $userFactory->definition(),
            $userFactory->definition(),

        );
    }

}
