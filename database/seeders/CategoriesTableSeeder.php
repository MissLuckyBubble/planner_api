<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
        ['title' => 'Красота', 'description' => 'салони за красота, фризьори, масажи, татуировки и пирсинг, солариум, спа' , 'created_at' => now()],
        ['title' => 'Спорт', 'description' => 'фитнес, аеробика, йога, танци', 'created_at' => now()],
        ['title' => 'Здраве', 'description' => 'лечение, медицина, психично здраве, здравославно хранене', 'created_at' => now()],
        ['title' => 'Забавление', 'description' => 'игри, групови занимания, занимания за деца', 'created_at' => now()],
        ['title' => 'Обучение', 'description' => 'уроци, онлайн уроци, курсове', 'created_at' => now()],
        ['title' => 'Автомобили', 'description' => 'Автокъщи, Автомивки, Бензиностанции, Дилъри, Курсове за шофьори, Мотори и мотопеди, Сервизи ', 'created_at' => now()]
    ]);
    }
}
