<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Wrap dalam transaction untuk memastikan data consistency
        DB::transaction(function () {
            $this->createRoles();
            $this->createUsers();
            $this->createSuperAdmin();
        });

        $this->command->info('✅ Roles and users seeded successfully!');
    }

    private function createRoles(): void
    {
        $this->command->info('Creating roles...');

        // Ambil semua permissions yang ada
        $allPermissions = Permission::all()->pluck('name')->toArray();

        $rolesConfig = [
            // Super Admin - full access
            'super_admin' => [
                'permissions' => $allPermissions,
                'description' => 'Full system access',
            ],

            // Panel User - basic access
            'panel_user' => [
                'permissions' => [
                    'View:Dashboard',
                ],
                'description' => 'Basic panel access',
            ],

            // Approver All Documents - dapat approve & melihat semua dokumen
            'approver_all_documents' => [
                'permissions' => [
                    'View:Dashboard',
                    'ViewAny:Category',
                    'View:Category',
                    // Tambahkan permissions untuk Document resource jika ada
                    // Sementara menggunakan Category sebagai contoh
                ],
                'description' => 'Can approve and view all documents',
            ],

            // Approver Lab & LSPRO Documents
            'approver_lab_lspro' => [
                'permissions' => [
                    'View:Dashboard',
                    'ViewAny:Category',
                    'View:Category',
                ],
                'description' => 'Can approve Lab and LSPRO documents',
            ],

            // Uploader Lab & LSPRO Documents
            'uploader_lab_lspro' => [
                'permissions' => [
                    'View:Dashboard',
                    'ViewAny:Category',
                    'View:Category',
                ],
                'description' => 'Can upload and view Lab and LSPRO documents',
            ],

            // Uploader Lab Documents Only
            'uploader_lab' => [
                'permissions' => [
                    'View:Dashboard',
                    'ViewAny:Category',
                    'View:Category',
                ],
                'description' => 'Can upload and view Lab documents only',
            ],

            // Viewer Lab Documents Only
            'viewer_lab' => [
                'permissions' => [
                    'View:Dashboard',
                    'ViewAny:Category',
                    'View:Category',
                ],
                'description' => 'Can only view Lab documents',
            ],
        ];

        foreach ($rolesConfig as $roleName => $config) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'web']
            );

            // Filter hanya permissions yang benar-benar ada di database
            $validPermissions = array_intersect($config['permissions'], $allPermissions);

            $role->syncPermissions($validPermissions);

            $this->command->info("  - Role '{$roleName}' created with " . count($validPermissions) . " permissions");
        }
    }

    private function createSuperAdmin(): void
    {
        $this->command->info('Creating Super Admin...');

        $superAdmin = User::firstOrCreate(
            ['email' => 'info@turanggatosan.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('SuperAdmin@2025'),
                'email_verified_at' => now(),
            ]
        );

        $superAdmin->syncRoles(['super_admin']);

        $this->command->warn("  ⚠️  Super Admin: info@turanggatosan.com | Password: SuperAdmin@2025");
    }

    private function createUsers(): void
    {
        $this->command->info('Creating users...');

        $users = [
            // Approver All Documents
            [
                'name' => 'Iwan Prijo Santoso',
                'email' => 'iwan.prijo@turanggatosan.com',
                'role' => 'approver_all_documents',
                'password' => 'Iwan@2025',
            ],
            [
                'name' => 'Syaefu Widodo',
                'email' => 'syaefu.widodo@turanggatosan.com',
                'role' => 'approver_all_documents',
                'password' => 'Syaefu@2025',
            ],
            [
                'name' => 'Murni Ati Putri',
                'email' => 'murni@turanggatosan.com',
                'role' => 'approver_all_documents',
                'password' => 'Murni@2025',
            ],

            // Approver Lab & LSPRO
            [
                'name' => 'Heru Prasetyo',
                'email' => 'heru@turanggatosan.com',
                'role' => 'approver_lab_lspro',
                'password' => 'Heru@2025',
            ],
            [
                'name' => 'Zahratul Syifa Aisya',
                'email' => 'syifa.aisya@turanggatosan.com',
                'role' => 'approver_lab_lspro',
                'password' => 'Syifa@2025',
            ],

            // Uploader Lab & LSPRO
            [
                'name' => 'Widiantari Nofriandani',
                'email' => 'widiantari@turanggatosan.com',
                'role' => 'uploader_lab_lspro',
                'password' => 'Widiantari@2025',
            ],
            [
                'name' => 'Riska Tamala',
                'email' => 'riska.tamala@turanggatosan.com',
                'role' => 'uploader_lab_lspro',
                'password' => 'Riska@2025',
            ],

            // Uploader Lab Only
            [
                'name' => 'Adi Sanrah',
                'email' => 'adi.sanrah@turanggatosan.com',
                'role' => 'uploader_lab',
                'password' => 'Adi@2025',
            ],
            [
                'name' => 'M. Anwar Hattabi',
                'email' => 'anwar.hattabi@turanggatosan.com',
                'role' => 'uploader_lab',
                'password' => 'Anwar@2025',
            ],

            // Viewer Lab Only
            [
                'name' => 'M. Solihin',
                'email' => 'm.solihin@turanggatosan.com',
                'role' => 'viewer_lab',
                'password' => 'Solihin@2025',
            ],
            [
                'name' => 'M. Isra',
                'email' => 'm.isra@turanggatosan.com',
                'role' => 'viewer_lab',
                'password' => 'Isra@2025',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles([$userData['role']]);

            $this->command->info("  - User: {$userData['name']} ({$userData['email']}) | Role: {$userData['role']}");
        }

        $this->command->warn("\n📋 Default password pattern: FirstName@2025");
        $this->command->warn("Example: Iwan@2025, Syaefu@2025, etc.");
    }
}
