{{--

    CUERPO GENÉRICO PARA LOS DATATABLES. PARA INTRODUCIR ESTE CUERPO GENERICO SE SEGUIRÁ EL SIGUIENTE MODELO:
    GENERIC BODY FOR DATATABLES. THE FOLLOWING MODEL SHALL BE USED TO INTRODUCE THIS GENERIC PATTERN:


buttons: [
    / / BOTONES PERSONALIZADOS
    / / CUSTOM BUTTONS
    / / POR EJEMPLO: / FOR EXAMPLE:
        {
            text: '{{ __('text.nuevoSocio')}}',
            className: "btn btn-sm btn-success",
            action: function ( e, dt, node, config ) {
                $('#nuevoSocio_modal').modal('toggle');
            }
        },
        ...

    / / E INCLUIMOS ESTE BLADE
    / / AND THIS BLDE INCLUDE

    @include('layouts.datatableComun')

--}}


    {
        extend: 'pageLength',
        className: "btn-sm btn-info",
    },
    {
        extend: 'colvis',
        className: 'btn btn-sm btn-primary',
        text: '{{ __('text.verColumnas')}}',
    },
    {
        extend: 'collection',
        className: 'btn btn-sm btn-dark',
        text: '{{ __('text.exportarDatos')}}',
            buttons: [
                {
                    extend:'copy',
                    titleAttr: '{{ __('text.copyPorta')}}',
                    text: 'Copiar',
                    exportOptions: {
                        modifier: {
                            page: 'all',
                            search: 'applied'
                        },
                        columns: ':visible :not(:last-child)',
                    },
                    action: function ( e, dt, node, config ) {
                        $resp = confirm("{{ __('text.avisoExportar')}}" );
                        if($resp == true){
                            $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config)
                        }
                    }
                },
                {
                    extend: 'print',
                    className: "btn btn-secondary",
                    text: '{{ __('text.imprimir')}}',
                    titleAttr: 'Imprimir',
                    exportOptions: {
                        columns: ':visible :not(:last-child)'
                    },
                    action: function ( e, dt, node, config ) {
                        $resp = confirm("{{ __('text.avisoExportar')}}");
                        if($resp == true){
                            $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, node, config)
                        }
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn btn btn-secondary',
                    titleAttr: '{{ __('text.exportExcel')}}',
                    text: 'Excel (XLSX)',
                    filename: 'excel-export',
                    extension: '.xlsx',
                    exportOptions : {
                        modifier : {
                            // DataTables core
                            order : 'index',  // 'current', 'applied', 'index',  'original'
                            page : 'all',      // 'all',     'current'
                            search : 'none'     // 'none',    'applied', 'removed'
                        },
                        columns: ':visible :not(:last-child)',
                    },
                    action: function ( e, dt, node, config ) {
                        $resp = confirm("{{ __('text.avisoExportar')}}");
                        if($resp == true){
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config)
                        }
                    }
                },
            ],
    }
],  //Finde Buttons
lengthMenu: [
    [ 10, 25, 50, -1 ],
    [ '10 {{ __('text.filas')}}', '25 {{ __('text.filas')}}', '50 {{ __('text.filas')}}', '{{ __('text.mostrarTodo')}}' ]
],
dom: 'Bfrtip',
language: {
    buttons: {
        copyTitle: '{{ __('text.copyPorta')}}',
        copyKeys: 'Presione <i> ctrl </i> o <i> \ u2318 </i> + <i> C </i> para copiar los datos de la tabla a su portapapeles. <br> <br> Para cancelar, haga clic en este mensaje o presione Esc.',
        copySuccess: {
            _: '%d líneas copiadas',
            1: '1 línea copiada'
        },
        pageLength: {
            _: "Mostrar %d filas",
            '-1': "Mostrar Todo"
        }
    }
},
