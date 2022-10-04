<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\Timeline;

class TimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Timeline::insert([
            'id' => helperUniqueId('timelines'),
            'title' => Str::random(10),
            'slug' => Str::random(10)
        ]);

    }
}
