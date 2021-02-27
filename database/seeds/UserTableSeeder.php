<?php

use Illuminate\Database\Seeder;
use App\Model\Admin;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $count = 1;
      factory(Admin::class, $count)->create();
    }
}
