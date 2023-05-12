<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionExpiresAtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::get();
        foreach ($users as $user) {
            DB::table('model_has_permissions')
                ->where('model_id', $user->id)
                ->update(['expires_at' => $user->store->expires_at]);
        }

    }
}
