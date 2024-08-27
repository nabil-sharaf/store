<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Admin\Order;
use App\Models\User;
use App\Models\Admin\Status;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Order::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users = User::all();
        $statuses = Status::all();

        foreach ($users as $user) {
            foreach ($statuses as $status) {
                Order::create([
                    'total_price' => rand(1000, 5000),
                    'user_id' => $user->id,
                    'status_id' => $status->id,

                ]);
            }
        }
    }
}

