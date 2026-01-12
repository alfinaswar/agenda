<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'agenda-list',
            'agenda-laporan',
            'agenda-edit',
            'agenda-delete',
            'meeting-list',
            'meeting-laporan',
            'meeting-edit',
            'meeting-delete',
            'event-list',
            'event-laporan',
            'event-edit',
            'event-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'master-ruangan-list',
            'master-ruangan-create',
            'master-ruangan-edit',
            'master-ruangan-delete',
            'pengaturan-home',
            'master-pegawai-list',
            'master-pegawai-create',
            'master-pegawai-edit',
            'master-pegawai-delete',
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }
    }
}
