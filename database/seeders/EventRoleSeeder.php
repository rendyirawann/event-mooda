<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Peran khusus Event Mooda (model komisi per tiket):
 * - organizer  : membuat & mengelola event + tiket
 * - affiliate  : promotor, bagikan link event, dapat komisi
 * - reseller   : dapat jatah tiket dari organizer, jual ulang
 * - buyer      : pembeli tiket (default user)
 */
class EventRoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (['organizer', 'affiliate', 'reseller', 'buyer'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $this->command->info('Peran event dibuat: organizer, affiliate, reseller, buyer');
    }
}
