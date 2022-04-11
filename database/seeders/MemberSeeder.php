<?php

namespace Database\Seeders;

use App\Models\Member;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MemberSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        /**
         * You may encounter General error: 1390 Prepared statement contains too many placeholders
         * This may happen because There is limit 65,535 (2^16-1) place holders in MariaDB & MySQL.
         * Can be solved by array_chunk function using a dynamic chunk size based on the number of placeholders/columns
         */

        $member = new Member;
        $table = $member->getTable();
        $columns = Schema::getColumnListing($table);

        $noOfColumns = count($columns); // determine number of columns in the table
        $noOfRows = 500000; // number of rows to be inserted 500k

        $range = range(1, $noOfRows);

        // calculate chunk size based on max placeholder / number of columns
        $chunkSize = 65535 / ($noOfColumns + 1); // adding extra + 1 to reduce result-set to be on safer side

        // takes 40-43 seconds
        foreach (array_chunk($range, $chunkSize) as $chunk) {
            $user_data = [];

            foreach ($chunk as $counter) {
                $user_data[] = [
                    'first_name' => $faker->firstName(),
                    'last_name' => $faker->lastName(),
                    'email' => $faker->unique()->safeEmail(),
                    'info' => 'Favorite number: ' . $counter,
                    'is_active' => rand(0, 1),
                ];
            }

            Member::insert($user_data);
        }

        // takes 68-70 seconds, almost 60% slower, takes double the time of above
        // $records = Member::factory($noOfRows)->make()->makeHidden(['full_name']);
        // $records->chunk($chunkSize)->each(function ($records) {
        //     Member::insert($records->toArray());
        // });

        // takes 400-425 seconds
        // DB::transaction(fn () => Member::factory($noOfRows)->create());
    }
}
