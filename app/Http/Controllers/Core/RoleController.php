<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleController extends Controller
{
    public function index()
    {
        if (!\Illuminate\Support\Facades\Gate::allows('manage-users')) {
            return redirect()->route('login');
        }

        $users = User::with(['roles', 'permissions'])->orderBy('created_at', 'desc')->get();
        
        $kabupatenList = \Illuminate\Support\Facades\DB::connection('mysql_knmp')
            ->table('knmp')
            ->select('kabupaten')
            ->distinct()
            ->whereNotNull('kabupaten')
            ->where('kabupaten', '!=', '')
            ->orderBy('kabupaten', 'asc')
            ->pluck('kabupaten');

        $staticPermissions = [
            'kelola_pengguna',
            'lihat_dashboard',
            'kelola_master_data',
            'kelola_operasional',
            'kelola_evaluasi',
            'unduh_laporan'
        ];

        // Ensure static permissions exist in DB
        foreach ($staticPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $fixedRoles = ['super_admin', 'admin', 'user_daerah'];
        foreach ($fixedRoles as $r) {
            Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
        }

        $roles = Role::whereIn('name', $fixedRoles)->get();
        
        // Fetch the static permissions to show as checkboxes
        $permissions = Permission::whereIn('name', $staticPermissions)->orderBy('name', 'asc')->get();

        return view('core.roles', [
            'activeModule' => 'Role & Permission', 
            'activeProgram' => 'Manajemen Sistem',
            'users' => $users,
            'kabupatenList' => $kabupatenList,
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array'
        ]);

        $roleName = strtolower(str_replace(' ', '_', $request->name));

        $role = Role::create(['name' => $roleName]);

        if ($request->has('permissions') && is_array($request->permissions)) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['message' => 'Role berhasil ditambahkan.']);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$id,
            'permissions' => 'nullable|array'
        ]);

        $roleName = strtolower(str_replace(' ', '_', $request->name));
        $role->name = $roleName;
        $role->save();

        if ($request->has('permissions') && is_array($request->permissions)) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        return response()->json(['message' => 'Role berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        // Prevent deleting system roles
        $systemRoles = ['super_admin', 'admin_roren', 'verifikator', 'user_daerah'];
        if (in_array($role->name, $systemRoles)) {
            return response()->json(['message' => 'Role sistem tidak dapat dihapus.'], 403);
        }

        $role->delete();

        return response()->json(['message' => 'Role berhasil dihapus.']);
    }
}
