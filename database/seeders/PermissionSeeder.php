<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permission::create(['name' => 'send-pass', 'guard_name' => 'api']);
        User::find(21)->givePermissionTo(['send-pass']);
        User::find(22)->givePermissionTo(['send-pass']);
        User::find(93)->givePermissionTo(['send-pass']);
        User::find(234)->givePermissionTo(['send-pass']);
        User::find(835)->givePermissionTo(['send-pass']);
        User::find(1014)->givePermissionTo(['send-pass']);
        User::find(1240)->givePermissionTo(['send-pass']);
        User::find(1635)->givePermissionTo(['send-pass']);
        User::find(2075)->givePermissionTo(['send-pass']);
        User::find(2111)->givePermissionTo(['send-pass']);
        // User::role('hyperion')->givePermissionTo(['send-pass']);
    }
}
