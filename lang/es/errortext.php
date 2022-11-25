<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Program text Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in the program text for various
    |  ERROR messages that are displayed to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    //MENSAJES DE OK-ERROR / OK-ERROR MESSAGES
    'porfaEscrMens' => 'Por favor, escribe un mensaje',
    'guardadoUsuario_exito' => 'Se han actualizado correctamente los datos del usuario',
    'eliminadoUsuario_exito' => 'El usuario ha sido eliminado con éxito',
    'guardado_fallo' => 'Debe rellenar todos los campos para poder guardar el fichero',
    'delArchivo_exito' => 'Archivo correctamente eliminado',
    'delArchivo_fallo' => 'El archivo no ha podido ser eliminado. No se encuentra ningún archivo con ese nombre.',
    'guardado_fallo_nofile' => 'No se ha seleccionado ningún fichero o no se ha podido subir al servidor',
    'noCuotaEncontrada' => 'No se ha encontrado ninguna cuota asociada a este socio',
    'noEventos' => 'No se han encontrado eventos asociados a este Socio',
    'emailEnviadoOK' => 'Se ha enviado el email correctamente. Se ha enviado una copia al remitente también',
    'ERRORseleccionarPropuesta' => '¡Error!: Debes seleccionar al menos una propuesta para borrar',
    'OKactualizadoLista' => 'Se ha actualizado la lista de propuestas correctamente',
    'OKcompraCreada' => 'Compra creada correctamente. Si se ha seleccionado que si, se habrá enviado un email a los participantes. Se han reseteado las votaciones',
    'OKeventoCreado' => 'Evento creado correctamente. Se ha enviado un email a los participantes (si así lo has seleccionado). Comprueba el calendario',
    'ERROReventoSinDescipcion' => '¡ATENCIÓN!: Es imposible crear un evento si no le pones una descripción',
    'OKpropuestaCreada' => 'Se ha creado la propuesta correctamente',
    'ERRORpropuestaCamposVacios' => '¡ATENCIÓN!: Es imposible crear una nueva propuesta si no están rellenos todos los campos',
    'OKvotos' => 'Tus votos han sido registrados correctamente',
    'ERRORconfigNoVocalia' => 'ERROR: Si quieres crear una nueva vocalía, debes rellenar al menos los campos Nombre y Descripción',
    'ERRORconfigNoCuota' => 'ERROR: Si quieres crear un nuevo tipo de cuota, debes rellenar al menos los campos Nombre, Descripción y Cantidad',
    'OKguardado' => 'Se ha guardado todo correctamente',
    'ERRORnoArchivo' => 'No se encuentra el archivo',
    'ERRORnoSocio' => 'ERROR. No se ha podido encontrar al socio',
    'OKgastaInvitacion' => 'Se ha procedido a gastar una invitación',
    'ERRORnoInvitaciones' => 'Alerta!: A este usuario no le quedan invitaciones',
    'OKreseteoInvitaciones' => 'Se ha reseteado correctamente el número de invitaciones',
    'OKreseteoInvitacionesTodas' => 'Se han reseteado todas las invitaciones y se ha procedido a asignar a los socios invitaciones según su cuota pagada',
    'OKdatosActualizados' => 'Los datos han sido actualizados correctamente',
    'OKimportacion' => 'Los datos han sido importados correctamente',
    'OKDatosCreados' => 'Los datos han sido creados correctamente',
    'OKLudotecaActualizada' => 'La ludoteca se ha sincronizado correctamente con la BGG',
    'OKHitoCreado' => 'Se ha creado un nuevo Hito en la Asociación, Comprueba el TimeLine',
    'ERRORHitoCreado' => 'Se ha producido un error al crear el archivo. Contacta con el Administrador',
    'OKEventoEliminado' => 'Se ha eliminado el evento correctamente',
    'OKInvitaacionRestaurada' => 'Se ha restaurado la invitación al socio',
    'OKNuevoApunte' => 'Se ha creado un apunte nuevo en las cuentas',
    'ERRORNuevoApunte' => 'ERROR: Se ha producido un error al guardar el apunte. Verifica que los datos son correctos',
    'OKNuevoApunteConCuota' => 'Se ha creado un nuevo apunte y se ha dado de alta la cuota del socio correspondiente',
    'OKCuotaActualizada' => 'Cuota actualizada correctamente. Su apunte también se ha modificado',
    'OKCuotaEliminada' => 'La cuota, y el asiento asociado a la misma, han sido eliminados con éxito.',
    'ERRORnoImagen' => 'ERROR: El formato seleccionado no corresponde al de una imagen. Por favor, selecciona un archivo JPG, JPEG, BMP, o PNG',
    'OKVocaliaGuardada' => 'Los cambios se han guardado correctamente en las vocalías',
    'OKSocioDesHab' => 'Se ha cambiado la situación del socio correctamente',
    'ERRORVocaliaNoEncontrada' => 'ERROR: La Vocalía no se encuentra. Error al obtener la vocalía',
    'OKemailEnviado' => 'Email enviado correctamente',
    'ERRORAplazamientoNoSocioSel' => 'ERROR: Debes seleccionar al menos un socio. O bien elegir la opción TODOS los socios del check',
    'OKAplazamientoCuotas' => 'Se han aplazado las cuotas correctamente. Por favor revísalas antes de continuar',
    'ERRORNoAutorizado' => 'ERROR: No dispones de privilegios suficientes para llevar a cabo esta acción',
    'OKSocioBorrado' => 'Se ha borrado correctamente al socio, eliminando también las cuotas, votaciones, eventos, propuestas, invitaciones, etc...',
    'ERRORNocomas' => 'ERROR: No se admiten comas en la cantidad. Por favor, utiliza el punto (.) para la separación de decimales (Ej: 35.60)',
    'ERRORUsernameCogido' => 'ERROR: El nombre de usuario ya está cogido. Los nombres de usuario deben ser únicos.',
    'ERROREmailCogido' => 'ERROR: El email ya está seleccionado por alguien anteriormente. No se pueden repetir los emails. Deben ser únicos',
    'ERRORrestoreHito' => 'ERROR: Ha ocurrido un error cuando se intentaba restaurar el archivo. Intenta ver el Log de eventos a ver si indica la causa.',
    'OKrestoreHito' => 'Se ha restaurado la página de HITOS correctamente',
    'ERRORNoLectXML' => 'Error: No se ha podido leer el fichero XML',
    'ERRORNoCuota' => 'ERROR:NO se ha encontrado ninguna cuota',
    'OKPanelActualizado' => 'El panel de noticias del Vocal se ha actualizado correctamente',
    'ERRORNoPresi' => 'No hay nombrado presidente',
    'ERRORNoSecre' => 'No hay nombrado secretario',
    'ERRORNoTeso' => 'No hay nombrado tesorero',
    'ERRORNoVocal' => 'No hay ningún vocal (todavía)',
    'OKCompActualizado' => 'Se ha cambiado la separación de votaciones entre compras Mayores (M) y compras Menores (P). Las votaciones antiguas NO se han borrado, así que es posible que algún socio tenga más de 3 votaciones. Por favor, comunica a los socios que actualicen sus votaciones.',
    'ERRORNoEncComp' => 'ERROR: No encuentro una compra asociada a esos valores o ha ocurrido algún error.',
    'OKCompElimin' => 'Se ha eliminado correctamente una compra. Se ha reintegrado la cantidad que costó al presupuesto de la Vocalía.',
    'ERRORnoCuotaSelectConfig' => 'ERROR: Has seleccionado el check de borrar una cuota pero no hay ninguna cuota seleccionada o ha habido algún error al  procesar la solicitud',
    'ERRORnoVocaliaSelectConfig' => 'ERROR: Has seleccionado el check de borrar una Vocalía pero no hay ninguna vocalía seleccionada o ha habido algún error al  procesar la solicitud',
    'ERRORconfigVocaliaDupli' => ' ERROR: Ya existe una vocalía con ese mismo nombre, por favor, selecciona otro',
    'ERRORcalendarioNoEncontrado' => 'ERROR: No se encuentra el calendario asociado a la dirección de email. Por favor, verifica que en el fichero de idioma está bien configurado los emails de Gmail',
];
