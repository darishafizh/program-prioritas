<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Get all users
        $users = DB::table('users')->get();
        
        $rolesToCreate = [
            'Super Admin' => 'super_admin',
            'super_admin' => 'super_admin',
            'Admin' => 'admin_roren',
            'Admin Roren' => 'admin_roren',
            'admin_roren' => 'admin_roren',
            'Verifikator' => 'verifikator',
            'User Daerah' => 'user_daerah',
            'user_daerah' => 'user_daerah',
            'Menteri' => 'menteri',
        ];

        // 2. Ensure standard roles exist
        foreach (array_unique(array_values($rolesToCreate)) as $roleName) {
            DB::table('roles')->insertOrIgnore([
                'name' => $roleName,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $rolesMap = DB::table('roles')->pluck('id', 'name')->toArray();

        // 3. Assign roles to users based on their old 'role' column
        foreach ($users as $user) {
            if (!isset($user->role) || !$user->role) continue;

            $oldRole = $user->role;
            $spatieRoleName = $rolesToCreate[$oldRole] ?? null;

            if (!$spatieRoleName) {
                // If not in map, just create it directly as lowercase snake_case
                $spatieRoleName = strtolower(str_replace(' ', '_', $oldRole));
                DB::table('roles')->insertOrIgnore([
                    'name' => $spatieRoleName,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $rolesMap = DB::table('roles')->pluck('id', 'name')->toArray(); // refresh map
            }

            $roleId = $rolesMap[$spatieRoleName] ?? null;

            if ($roleId) {
                DB::table('model_has_roles')->insertOrIgnore([
                    'role_id' => $roleId,
                    'model_type' => 'App\Models\User',
                    'model_id' => $user->id,
                ]);
            }
        }

        // 4. Drop the old role column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Restore the role column
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable();
        });

        // 2. Try to restore data based on spatie roles
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $roleRecord = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_id', $user->id)
                ->where('model_type', 'App\Models\User')
                ->first();
            
            if ($roleRecord) {
                // Try to format back (e.g. super_admin -> Super Admin)
                $oldRoleName = ucwords(str_replace('_', ' ', $roleRecord->name));
                DB::table('users')->where('id', $user->id)->update(['role' => $oldRoleName]);
            }
        }
    }
};
