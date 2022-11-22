<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use App\Vocalia;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::create(['name' => 'admin']);
        $role_tesorero = Role::create(['name' => 'tesorero']);
        $role_secretario = Role::create(['name' => 'secretario']);
        $role_junta = Role::create(['name' => 'junta']);
        $role_socio = Role::create(['name' => 'socio']);
        $role_vocal = Role::create(['name' => 'vocal']); //EL ROL VOCAL NO SE ASIGNA A NADIE. SE DEBE ASIGNAR EL PERMISO permiso_X CORRESPONDIENTE

        $permission = Permission::create(['name' => 'Acceso_total']);
			$permission->assignRole($role_admin);
		$permission = Permission::create(['name' => 'socios']);
			$permission->assignRole($role_socio);
			$permission->assignRole($role_admin);
			$permission->assignRole($role_junta);
		$permission = Permission::create(['name' => 'permiso_tesoreria']);
			$permission->assignRole($role_tesorero);
			$permission->assignRole($role_admin);
		$permission = Permission::create(['name' => 'permiso_secretaria']);
			$permission->assignRole($role_secretario);
			$permission->assignRole($role_admin);
		$permission = Permission::create(['name' => 'permiso_ver_informes']);
			$permission->assignRole($role_junta);
			$permission->assignRole($role_secretario);
			$permission->assignRole($role_tesorero);
			$permission->assignRole($role_admin);
		$permission = Permission::create(['name' => 'permiso_editar_socios']);
			$permission->assignRole($role_secretario);
			$permission->assignRole($role_tesorero);
			$permission->assignRole($role_admin);

        $vocalias = Vocalia::all();
        foreach ($vocalias as $vocalia) {
        	$nombre = $vocalia->nombre;
        	$permission = Permission::create(['name' => 'permiso_vocalia_'.$nombre]);
        }

    }
}
