<script type="text/javascript">
    function cambiabtnaccept() {
        var aux = document.getElementById('descript').checked;
        if(aux) {
            document.getElementById('btnaccept').disabled = false;
        } else {
            document.getElementById('btnaccept').disabled = true;
        }
    }


    // Concept: Render select2 fields after all javascript has finished loading
    var initSelect2 = function(){
        // function that will initialize the select2 plugin, to be triggered later
        var renderSelect = function(){
            $('section#formSection select').each(function(){
                $(this).select2({
                    'dropdownCssClass': 'dropdown-hover',
                    'width': '',
                    'minimumResultsForSearch': -1,
                });
            })
        };
        // create select2 HTML elements
        var style = document.createElement('link');
        var script = document.createElement('script');
        style.rel = 'stylesheet';
        style.href = 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css';
        script.type = 'text/javascript';
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.full.min.js';
        // trigger the select2 initialization once the script tag has finished loading
        script.onload = renderSelect;
        // render the style and script tags into the DOM
        document.getElementsByTagName('head')[0].appendChild(style);
        document.getElementsByTagName('head')[0].appendChild(script);
    };
    initSelect2();

    $(document).ready(function() {
        $(".reveal").on('click',function() {
            var $pwd = $(".pwd");
            if ($pwd.attr('type') === 'password') {
                $pwd.attr('type', 'text');
            } else {
                $pwd.attr('type', 'password');
            }
        });
        $('.js-example-basic-single').select2();

        $.ajax({
                url: "{{url('/listaSocios')}}",
                success : function(data) {
                    var tabla = data.data;//.list.item;
                    $('#tablaSocios').DataTable( {
                        responsive: true,
                        processing: true,
                        colReorder: false,
                        serverSide: true,
                        "order": [[ 1, "desc" ]],
                        ajax: '{{url('/listaSocios')}}',
                        initComplete: function () {
                            this.api().columns(1).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(2).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(3).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(4).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(5).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(6).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(7).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(8).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(9).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(10).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(11).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(12).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(13).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(14).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(15).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(16).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(17).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(18).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(19).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                        },
                        lengthMenu: [[10, 25, 50, -1], ["10 filas", "25 filas", "50 filas", "Todos"]],
                        dom: 'Bfrtip',
                        language: {
                            buttons: {
                                copyTitle: 'Copiar al Portapeles',
                                copyKeys: 'Presione <i> ctrl </i> o <i> \ u2318 </i> + <i> C </i> para copiar los datos de la tabla a su portapapeles. <br> <br> Para cancelar, haga clic en este mensaje o presione Esc.',
                                copySuccess: {
                                    _: '%d líneas copiadas',
                                    1: '1 línea copiada'
                                },
                                pageLength: {
                                    _: "Mostrando %d filas",
                                    '-1': "Mostrar todo"
                                }
                            }
                        },
                        data : tabla,
                        columns: [
                            {
                              "className": 'details-control',
                              "orderable": false,
                              "data": "foto",
                              "visible": true,
                              "searchable": false,
                              "render": function (data, type, row) {
                                  return "<img src=\"" +data+"\" style=\"width: 50px; height: 50px; position: center; top: 10px; left: 30px;\"></img>";
                              }
                            },
                            {
                                "data" : "numSocio",
                                "visible": true,
                            },
                            {
                                "data" : "alta",
                                "visible": false,
                            },
                            {
                                "data" : "baja",
                                "visible": false,
                            },
                            {
                                "data" : "nombre",
                                "visible": true,
                            },
                            {
                                "data" : "primerApellido",
                                "visible": true,
                            },
                            {
                                "data" : "segundoApellido",
                                "visible": true,
                            },
                            {
                                "data" : "fnacimiento",
                                "visible": false,
                            },
                            {
                                "data" : "DNI",
                                "visible": true,
                            },
                            {
                                "data" : "email",
                                "visible": true,
                            },
                            {
                                "data" : "telefono",
                                "visible": true,
                            },
                            {
                                "data" : "habilitado",
                                "visible": true,
                            },
                            {
                                "data" : "direccion",
                                "visible": false,
                            },
                            {
                                "data" : "localidad",
                                "visible": false,
                            },
                            {
                                "data" : "provincia",
                                "visible": false,
                            },
                            {
                                "data" : "username",
                                "visible": true,
                            },
                            {
                                "data" : "sexo",
                                "visible": false,
                            },
                            {
                                "data" : "accs1",
                                "visible": false,
                            },
                            {
                                "data" : "accs2",
                                "visible": false,
                            },
                            {
                                "data" : "notas",
                                "orderable": false,
                                "visible": false,
                            },
                            {
                                "className": 'details-control',
                                "visible":true,
                                "orderable": false,
                                "data": null,
                                 "render": function (data, type, row) {
                                    return "<a href=\"{{url('/ficha/')}}/"+ row.id +"\"><input type=\"button\" class=\"btn btn-outline-success btn-sm\" value=\"&#128393\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"{{__('text.editaSocioHelp') }}\"/></a>&nbsp;<a href=\"{{url('/eliminaFicha/')}}/"+ row.id +"\"><input type=\"button\" class=\"btn btn-outline-danger btn-sm\" value=\"&#10007\" onclick=\"return confirm('{{ __('text.QuestionSuredeleteSocio')}}')\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"{{__('text.eliminaSocioHelp') }}\"/></a>";
                                }
                            },
                        ],
                        buttons: [
                            {
                                text: '{{ __('text.nuevoSocio')}}',
                                className: "btn btn-sm btn-success",
                                action: function ( e, dt, node, config ) {
                                    $('#nuevoSocio_modal').modal('toggle');
                                }
                            },
                            @include('layouts.datatableComun')
                    });
                },
            });
    }); //Fin de Document Ready
</script>
