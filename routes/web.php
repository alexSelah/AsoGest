<?php

use Illuminate\Support\Env;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*PRUEBAS*/
Route::get('/pruebas', 'Pruebas@pruebas');

/*HOME O INICIO*/
Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::get('/home', 'HomeController@index')->name('home');

Auth::routes(['register' => false]);

/*RUTAS DE CONTACTO. PAGINA WEB PRINCIPAL O ALTERNATIVA*/
Route::get('/webPortal',function(){
	return redirect('https://www.google.es');
})->name('webPortal');

/*RUTAS DE GESTION DE FICHA*/
Route::get('/ficha/{idSocio?}', 'GestionFichaController@index')->name('gestionficha');
Route::get('/resetearInvitaciones/{idSocio?}', 'GestionFichaController@resetearInvitaciones')->name('resetearInvitaciones');
Route::post('/usuariostore', 'GestionFichaController@store')->name('usuarioStore');
Route::post('/gastarInvitacion', 'GestionFichaController@gastarInvitacion')->name('gastarInvitacion');
Route::get('/eliminaEvento/{idEvento}', 'GestionFichaController@eliminaEvento')->name('eliminaEvento');

/*RUTAS DE ACCIONES RAPIDAS DE HOME*/
Route::get('/crearEventoRapido', 'HomeController@crearEventoRapido')->name('crearEventoRapido');
Route::get('/apunteRapido', 'HomeController@apunteRapido')->name('apunteRapido');
Route::get('/verCalendario', 'HomeController@verCalendario')->name('verCalendario');
Route::post('/guardaEventoHome', 'HomeController@guardaEventoHome')->name('guardaEventoHome');

/*RUTAS PARA MOVIL*/
Route::get('/movil', 'MovilController@index')->name('movil');
Route::get('/gastarInvitacionMovil/{idSocio}', 'MovilController@gastarInvitacionMovil')->name('gastarInvitacionMovil');
Route::post('movilInvitacion','MovilController@movilInvitacion')->name('movilInvitacion');
Route::post('/guardaFichaMovil', 'MovilController@guardaFichaMovil')->name('guardaFichaMovil');
Route::get('/pag1/{id?}', 'MovilController@pag1')->name('pag1');
Route::get('/pag2', 'MovilController@pag2')->name('pag2');
Route::get('/pag3', 'MovilController@pag3')->name('pag3');
Route::get('/pag5', 'MovilController@pag5')->name('pag5');
Route::get('/pag6', 'MovilController@pag6')->name('pag6');
Route::get('/pag7', 'MovilController@pag7')->name('pag7');
Route::get('/pag8', 'MovilController@pag8')->name('pag8');
Route::get('/pag9', 'MovilController@pag9')->name('pag9');
Route::get('/vocaliaMovil/{idVocalia}', 'MovilController@vocalia')->name('vocaliaMovil');
Route::post('/votarPropuestaMovil', 'MovilController@votarPropuestaMovil')->name('votarPropuestaMovil');
Route::post('/votarPropuestaMovilMP', 'MovilController@votarPropuestaMovilMP')->name('votarPropuestaMovilMP');
Route::post('/guardaPropuestaMovil', 'MovilController@guardaPropuestaMovil')->name('guardaPropuestaMovil');
Route::post('/guardarEventoMovil', 'MovilController@guardarEventoMovil')->name('guardarEventoMovil');
Route::post('/nuevoApunteMovil', 'MovilController@nuevoApunteMovil')->name('nuevoApunteMovil');


/*RUTAS DE GESTION DE ASOCIACION*/
Route::get('/asociacion', 'AsociacionController@index')->name('asociacion');
Route::post('/guardarEventoAsoc', 'AsociacionController@store')->name('guardarEventoAsoc');
Route::get('/juegosDataTable', 'AsociacionController@create')->name('juegosDataTable');
Route::get('/actualizaLudoteca', 'AsociacionController@actualizaLudoteca')->name('actualizaLudoteca');
Route::post('/crearHito', 'AsociacionController@crearHito')->name('crearHito');
Route::get('/reseteaHitos', 'AsociacionController@reseteaHitos')->name('reseteaHitos');


/*RUTAS DE DOCUMENTOS*/
Route::get('/documentos', 'DocumentoController@index')->name('documentos');
Route::post('/subeDocumento', 'DocumentoController@store')->name('subeDocumento');
Route::get('/descargaDocumento/{doc}', 'DocumentoController@download')->name('descargaDocumento');
Route::get('/eliminaDocumento/{doc}', 'DocumentoController@destroy')->name('eliminaDocumento');
Route::get('/documentosDataTable', 'DocumentoController@documentosDataTable')->name('documentosDataTable');


/*RUTAS DE SECRETARIO*/
Route::get('/secretario', 'SecretarioController@index')->name('secretario');
Route::get('/secretario/fichaSocioNueva/{idSocio}', 'SecretarioController@fichaSocioNueva')->name('fichaSocioNueva');
Route::get('/listaSocios', 'SecretarioController@sociosDatatables')->name('listaSocios');
Route::post('/secretarioStore', 'SecretarioController@store')->name('secretarioStore');
Route::post('/nuevoSocio', 'SecretarioController@create')->name('nuevoSocio');
Route::post('/secretarioImport', 'SecretarioController@secretarioImport')->name('secretarioImport');
Route::get('/descargaExcelSocios', 'SecretarioController@descargaExcelSocios')->name('descargaExcelSocios');
Route::get('/eliminaFicha/{idSocio?}', 'SecretarioController@destroy')->name('eliminaFicha');

/*RUTAS DE TESORERO*/
Route::get('/tesorero', 'TesoreroController@index')->name('tesorero');
Route::get('/tesoreroFechas/{f1?}/{f2?}', 'TesoreroController@index')->name('tesoreroFechas');
Route::post('/nuevoApunte', 'TesoreroController@create')->name('nuevoApunte');
Route::get('/cuentas/{f1}/{f2}', 'TesoreroController@cuentasDatatables')->name('cuentasDatatables');
Route::get('/cuotas/{vt}', 'TesoreroController@cuotas')->name('cuotas');
Route::get('/cuotasDatatable/{vt?}', 'TesoreroController@cuotasDatatable')->name('cuotasDatatable');
Route::post('/nuevaCuota', 'TesoreroController@nuevaCuota')->name('nuevaCuota');
Route::get('/eliminaApunte/{apunte}', 'TesoreroController@destroy')->name('eliminaApunte');
Route::get('/editarApunte/{apunte}', 'TesoreroController@edit')->name('editarApunte');
Route::post('/guardarApunte', 'TesoreroController@update')->name('guardarApunte');
Route::post('/tesoreroImport', 'TesoreroController@tesoreroImport')->name('tesoreroImport');
Route::get('/gastos_ingresos', 'TesoreroController@gastos_ingresos')->name('gastos_ingresos');
Route::post('/actualizaCuentas', 'TesoreroController@update')->name('actualizaCuentas');
Route::get('/descargaExcel', 'TesoreroController@show')->name('descargaExcel');
Route::get('/datosXanio/{year?}', 'TesoreroController@datosXanio')->name('datosXanio');
Route::get('/datosXanioDatatable/{year}', 'TesoreroController@datosXanioDatatable');
Route::get('/mantenimientoPDF', 'TesoreroController@mantenimientoPDF')->name('mantenimientoPDF');
Route::get('editaCuota/{id}',['as'=>'dynamicModal', 'uses'=> 'TesoreroController@editaCuota']);
Route::post('/actualizaCuota', 'TesoreroController@actualizaCuota')->name('actualizaCuota');
Route::get('/eliminaCuota/{id}', 'TesoreroController@eliminaCuota')->name('eliminaCuota');
Route::get('/deshabilitaSocio/{id}', 'TesoreroController@deshabilitaSocio')->name('deshabilitaSocio');
Route::get('/vocaliasTesorero', 'TesoreroController@vocaliasTesorero')->name('vocaliasTesorero');
Route::post('/guardaVocaliasTesorero', 'TesoreroController@guardaVocaliasTesorero')->name('guardaVocaliasTesorero');
Route::get('enviaEmailCuota/{id}',['as'=>'dynamicModal', 'uses'=> 'TesoreroController@enviaEmailCuota']);
Route::post('/enviaEmail', 'TesoreroController@enviaEmail')->name('enviaEmail');
Route::post('/aplazaCuotas', 'TesoreroController@aplazaCuotas')->name('aplazaCuotas');
Route::post('/informePersTesorero', 'TesoreroController@informePersTesorero')->name('informePersTesorero');

/*RUTAS DE VOCALIA*/
Route::get('/vocalia/{id}', 'VocaliaController@index')->name('vocalia');
Route::post('/votarPropuesta', 'VocaliaController@votarPropuesta')->name('votarPropuesta');
Route::post('/votarPropuestaMP', 'VocaliaController@votarPropuestaMP')->name('votarPropuestaMP');
Route::post('/guardaEvento', 'VocaliaController@guardaEvento')->name('guardaEvento');
Route::post('/guardaPropuesta', 'VocaliaController@guardaPropuesta')->name('guardaPropuesta');
Route::post('/emailVocal', 'VocaliaController@emailVocal')->name('emailVocal');
Route::post('/crearCompra', 'VocaliaController@crearCompra')->name('crearCompra');
Route::post('/eliminaPropuesta', 'VocaliaController@eliminaPropuesta')->name('eliminaPropuesta');
Route::get('/comprasDatatable/{id?}', 'VocaliaController@comprasDatatable')->name('comprasDatatable');
Route::post('/guardaPanelVocal', 'VocaliaController@guardaPanelVocal')->name('guardaPanelVocal');
Route::get('/cambiaSepComp/{id}', 'VocaliaController@cambiaSepComp')->name('cambiaSepComp');
Route::get('/eliminaCompra/{idVocalia}/{idCompra}', 'VocaliaController@eliminaCompra')->name('eliminaCompra');

/*RUTAS DE TOUR*/
Route::get('/tour',function(){return view('tour');})->name('tour');

//Route::get('/mail/send', 'MailController@send');

/*RUTAS CONFIGURACION*/
Route::get('/configura', 'ConfiguraController@index')->name('configura');
Route::post('guardaConfiguras', 'ConfiguraController@store')->name('guardaConfiguras');
Route::get('/visor', 'ConfiguraController@visor')->name('visor');



/*Gestion Invitaciones*/
Route::get('/secretario/visorInvitaciones', 'InvitacionesController@index')->name('visorInvitaciones');
Route::get('/invitacionesDataTable', 'InvitacionesController@create')->name('invitacionesDataTable');
Route::post('gastarInvitacionSocio', 'InvitacionesController@store')->name('gastarInvitacionSocio');
Route::get('/eliminarInvitacion/{idIvitacion}', 'InvitacionesController@destroy')->name('eliminarInvitacion');
Route::get('/resetearTodasInvitaciones', 'InvitacionesController@destroyAll')->name('resetearTodasInvitaciones');

Route::get('prohibido', function () {
    return view('/prohibido');
})->name('prohibido');
Route::get('juegonoencontrado', function () {
    return view('/juegonoencontrado');
})->name('juegonoencontrado');
Route::get('about', function () {
    return view('/about');
})->name('about');

Route::get('/clear-cache/{pass?}', function () {
    if($pass == 'limpia')
    {
        echo "Config Clear: ".Artisan::call('config:clear');
        echo " Config Cache: ".Artisan::call('config:cache');
        echo " Cache Clear: ".Artisan::call('cache:clear');
        echo " Route Clear: ".Artisan::call('route:clear');
        echo " -  OPTIMIZE CLEAR: ".Artisan::call('optimize:clear');
    }else{
        dd("EEROR, no se puede limpiar cache");
        return abort(404);
    }
});
