<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Str;
use Carbon\Carbon;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('leads')->insert([
            'name' => Str::random(10),
        ],
            [
                'name' => 'Lead2',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Lead3',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
    }

}
