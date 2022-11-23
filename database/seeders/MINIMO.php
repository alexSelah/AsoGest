<?php

namespace Database\Seeders;

use App\Configura;
use App\TiposCuota;
use App\User;
use App\Vocalia;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class MINIMO extends Seeder
{
    // ESTO ES LO MÍNIMO CON LO QUE SE DEBE ALIMENTAR LA BASE DE DATOS PARA QUE FUNCIONE EL PROGRAMA
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* 1.- PERMISOS
            1.- PERMISSIONS*/
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
            $permission->assignRole($role_secretario);
			$permission->assignRole($role_tesorero);
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

        /*2.- UN TIPO DE CUOTA DE SOCIO
            2.- ONE TYPE OF MEMBERSHIP FEE*/
        $cuotaMensual = TiposCuota::create([
            'nombre' => "Mensual",
            'descripcion' => "Cuota mensual, se cobra el día 1 de cada mes",
            'meses'=> 1,
            'cantidad' => 10,
            'invitaciones' => 1,
        ]);

        /* 3.- AL MENOS CREACIÓN DEL USUARIO ADMINISTRADOR
            3.- MIMIMUM ADMINISTRATOR CREATION*/
        $usuario = User::create([
            'numSocio' => '1',
            'altaSocio' => Carbon::now()->addMonths(-1),
            'bajaSocio' => null,
            'nombre' => 'Administrador',
            'primerApellido' => 'Asogest',
            'segundoApellido' => '',
            'DNI' => '00000001X',
            'sexo' => 'varon',
            'fnacimiento' => Carbon::createFromFormat('d-m-Y', "01-01-2000", 'Europe/Madrid'),
            'direccion' => 'Calle de Asogest, 1',
            'localidad' => 'Madrid',
            'provincia' => 'Madrid',
            'codPostal' => 28028,
            'telefono' => '666000111',
            'email' => 'admin@asogest.com',
            'password' => bcrypt('Admin1'),
            'foto' => '',
            'invitaciones' => '6',
            'username' => 'admin',
            'habilitado' => true,
            'accesoDrive' => true,
            'accesoJunta' => true,
            'recibirCorreos' => true,
            'privacidad' => true,
        ]);
        $usuario->assignRole('admin');

        /* 4.- ASIGNACIÓN DE LOS PERMISOS A LAS VOCALIAS
            4.- ASSIGNMENT OF PERMITS TO SECTIONS*/
        $vocalias = Vocalia::all();
        if($vocalias != null){
            foreach ($vocalias as $vocalia) {
                $nombre = $vocalia->nombre;
                $permission = Permission::create(['name' => 'permiso_vocalia_'.$nombre]);
            }
        }

        /* 6.- CONFIGURACIÓN MINIMA
            6.- MINIMUM CONFIGURATION*/
        $configura = Configura::create([
            'nombre' => 'CM',
            'descripcion' => 'Cuota de Mantenimiento de Local (POR SOCIO)',
            'valorNumero' => 7.5,
            'valorTexto' => null,
        ]);
        /* Instrucciones BGG : https://boardgamegeek.com/wiki/page/BGG_XML_API#  */
        /* Instructions BGG : https://boardgamegeek.com/wiki/page/BGG_XML_API#  */
        $configura = Configura::create([
        	'nombre' => 'URLBGG',
        	'descripcion' => 'Dirección del fichero XML de la BGG de la Asociación (ver API de la BGG para XML)',
        	'valorNumero' => null,
        	'valorTexto' => 'https://boardgamegeek.com/xmlapi/collection/eekspider',
        ]);
        $configura = Configura::create([
            'nombre' => 'IdCalendarioImportantes',
            'descripcion' => 'ID del Calendario IMPORTANTES (Para eventos importantes o de toda la Asociación)',
            'valorNumero' => null,
            'valorTexto' => '0vjdb3cvdh3tr9r0je8gv3rm3s@group.calendar.google.com',
        ]);
        $configura = Configura::create([
            'nombre' => 'CALURL',
            'descripcion' => 'Dirección pública del calendario (para compartir)',
            'valorNumero' => null,
            'valorTexto' => 'https://calendar.google.com/calendar/embed?src=asogest2020%40gmail.com&ctz=Europe%2FMadrid',
        ]);
        $configura = Configura::create([
            'nombre' => 'CAL1',
            'descripcion' => 'Iframe del Calendario de Google de INICIO',
            'valorNumero' => null,
            'valorTexto' => '<iframe src="https://calendar.google.com/calendar/embed?height=600&amp;wkst=2&amp;bgcolor=%23ffffff&amp;ctz=Europe%2FMadrid&amp;src=YXNvZ2VzdDIwMjBAZ21haWwuY29t&amp;color=%23039BE5&amp;showNav=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;showTitle=1&amp;title=Pr%C3%B3ximos%20Eventos&amp;mode=AGENDA" style="border:solid 1px #777" width="400" height="600" frameborder="0" scrolling="no"></iframe>',
        ]);
        $configura = Configura::create([
            'nombre' => 'CAL2',
            'descripcion' => 'Iframe del Calendario Izquierdo de ASOCIACION',
            'valorNumero' => null,
            'valorTexto' => '<iframe src="https://calendar.google.com/calendar/embed?height=600&amp;wkst=2&amp;bgcolor=%23ffffff&amp;ctz=Europe%2FMadrid&amp;src=YXNvZ2VzdDIwMjBAZ21haWwuY29t&amp;color=%23039BE5&amp;showNav=1&amp;showDate=0&amp;showPrint=1&amp;showTabs=1&amp;showCalendars=0&amp;showTz=0&amp;showTitle=1&amp;title=Pr%C3%B3ximos%20Eventos&amp;mode=WEEK" style="border:solid 1px #777" width="400" height="600" frameborder="0" scrolling="no"></iframe>',
        ]);
        $configura = Configura::create([
            'nombre' => 'CAL3',
            'descripcion' => 'Iframe del Calendario Derecho de ASOCIACION',
            'valorNumero' => null,
            'valorTexto' => '<iframe src="https://calendar.google.com/calendar/embed?height=600&amp;wkst=2&amp;bgcolor=%23ffffff&amp;ctz=Europe%2FMadrid&amp;src=YXNvZ2VzdDIwMjBAZ21haWwuY29t&amp;color=%23039BE5&amp;showNav=1&amp;showDate=0&amp;showPrint=1&amp;showTabs=1&amp;showCalendars=0&amp;showTz=0&amp;showTitle=1&amp;title=Pr%C3%B3ximos%20Eventos&amp;mode=AGENDA" style="border:solid 1px #777" width="400" height="600" frameborder="0" scrolling="no"></iframe>',
        ]);
        $configura = Configura::create([
            'nombre' => 'CAL4',
            'descripcion' => 'Iframe del Calendario en la vista MOVIL',
            'valorNumero' => null,
            'valorTexto' => '<iframe src="<iframe src="https://calendar.google.com/calendar/embed?height=600&amp;wkst=2&amp;bgcolor=%23ffffff&amp;ctz=Europe%2FMadrid&amp;src=YXNvZ2VzdDIwMjBAZ21haWwuY29t&amp;color=%23039BE5&amp;showNav=1&amp;showDate=0&amp;showPrint=1&amp;showTabs=1&amp;showCalendars=0&amp;showTz=0&amp;showTitle=1&amp;title=Pr%C3%B3ximos%20Eventos&amp;mode=AGENDA" style="border:solid 1px #777" width="400" height="600" frameborder="0" scrolling="no"></iframe>',
        ]);
        $configura = Configura::create([
            'nombre' => 'CMEN',
            'descripcion' => 'Cantidad por la que se considera Compra Menor (en €)',
            'valorNumero' => 35,
            'valorTexto' => null,
        ]);
        $configura = Configura::create([
            'nombre' => 'CHATS',
            'descripcion' => 'Grupos de Chat. NOMBRE>DESCRIPCION>URL>NOMBRE2>DESC2>URL2... (Obligatorio ese formato. Todos los campos rellenos, Separados por >)',
            'valorNumero' => null,
            'valorTexto' => "TELEGRAM del Autor>Telegram del autor de ASOGEST>https://t.me/alexselah",
        ]);
        $configura = Configura::create([
            'nombre' => 'logoBase64',
            'descripcion' => 'Logotipo de la Asociación en Base 64',
            'valorNumero' => null,
            'valorTexto' => "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",
        ]);
    }
}
