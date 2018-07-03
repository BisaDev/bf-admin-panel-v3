<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Brightfox\Models\SatSection;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'director']);
        Role::create(['name' => 'instructor']);
        SatSection::create(['name' => 'Reading Comprehension']);
        SatSection::create(['name' => 'Writing and Language']);
        SatSection::create(['name' => 'Math No Calculator']);
        SatSection::create(['name' => 'Math With Calculator']);
    }
}
