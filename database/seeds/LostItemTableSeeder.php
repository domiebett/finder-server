<?php

use Illuminate\Database\Seeder;

class LostItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\LostItem::class, 40)->create();
    }
}
