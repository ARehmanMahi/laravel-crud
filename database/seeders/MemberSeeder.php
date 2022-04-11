<?php namespace Database\Seeders;
use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder {
    public function run() {
        $faker = \Faker\Factory::create();

        /**
         * You may encounter General error: 1390 Prepared statement contains too many placeholders
         * This may happen because There is limit 65,535 (2^16-1) place holders in MariaDB & MySQL.
         * Can be solved by array_chunk function using a dynamic chunk size based on the number of placeholders/columns
         */

        $noOfColumns = 13; // determine number of columns in the table
        $noOfRows = 500000; // number of rows to be inserted 500k

        $range = range(1, $noOfRows);

        // calculate chunk size based on max placeholder / number of columns
        $chunkSize = 65535 / ($noOfColumns + 1); // adding + 1 to fix rounding problem to be on safer side

        foreach (array_chunk($range, $chunkSize) as $chunk) {
            $user_data = [];

            foreach ($chunk as $i) {
                $num = rand(0, 1);
                $user_data[] = [
                    'first_name' => $faker->firstName(),
                    'last_name' => $faker->lastName(),
                    'email' => $faker->unique()->safeEmail(),
                    'info' => 'Favorite number: ' . $i . $num,
                    'is_active' => $num,
                ];
            }

            Member::insert($user_data);
        }
    }
}
