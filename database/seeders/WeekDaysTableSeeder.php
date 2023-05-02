<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeekDaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('week_days')->insert([
            [
                'id' => 1,
                'name' => 'Понеделинк',
                'name_eng' => 'Monday'
            ],
            [
                'id' => 2,
                'name' => 'Вторник',
                'name_eng' => 'Tuesday'

            ],[
                'id' => 3,
                'name' => 'Сряда',
                'name_eng' => 'Wednesday'
            ],
            [
                'id' => 4,
                'name' => 'Четвъртък',
                'name_eng' => 'Thursday'

            ],[
                'id' => 5,
                'name' => 'Петък',
                'name_eng' => 'Friday'

            ],
            [
                'id' => 6,
                'name' => 'Събота',
                'name_eng' => 'Saturday'

            ], [
                'id' => 7,
                'name' => 'Неделя',
                'name_eng' => 'Sunday'

            ],
        ]);
    }
}
