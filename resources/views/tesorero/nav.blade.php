  <nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
      <ul class="nav flex-column">
        <li class="nav-item">
          <div class="row" data-toggle="modal" data-target="#apunteRapido_modal" style="margin-top:5%">
              <a class="nav-link" data-toggle="modal" data-target="#apunteRapido_modal" href="#">
                &#128393;&nbsp;{{ __('text.apunteRapido') }}
              </a>
          </div>
        </li>
        <li class="nav-item">
          <div class="row" >
            <a class="nav-link" href="{{ route('gastos_ingresos') }}">
                &#128202;&nbsp;{{ __('text.irContabilidad') }}
            </a>
          </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">	&#128229;&nbsp;{{ __('text.gestMasCuentas') }}</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ url('/descargaExcel') }}">
                    &#128317;&nbsp;{{ __('text.descargarPlantilla') }}
                </a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#importarExcel_modal">
                    &#128200;&nbsp;{{ __('text.importarExcel') }}
                </a>
            </div>
        </li>

        <li class="nav-item">
          <div class="row">
              <a class="nav-link" href="{{ url('/cuotas/null') }}">
                &#128188;&nbsp;{{ __('text.irCuotas') }}
              </a>
        </li>
      </ul>

      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>&#128221;&nbsp;{{ __('text.navInformesymas') }}</span>
        <a class="d-flex align-items-center text-muted" href="#">
          <span data-feather="plus-circle"></span>
        </a>
      </h6>
      <ul class="nav flex-column mb-2">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('datosXanio') }}">
            <span data-feather="file-text" data-toggle="tooltip" data-placement="top" title="{{__('text.ingresosGastosAnoDesc')}}">
            {{__('text.ingresosGastosAno')}}
            </span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" data-toggle="modal" data-target="#mantenimiento_modal">
            <span data-feather="file-text" data-toggle="tooltip" data-placement="top" title="{{__('text.mantenimientoDesc')}}">
            {{__('text.mantenimiento')}}
            </span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" data-toggle="modal" data-target="#inforPers_modal">
            <span data-feather="file-text" data-toggle="tooltip" data-placement="top" title="{{__('text.informePersDesc')}}">
            {{__('text.informePers')}}
            </span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('vocaliasTesorero') }}">
            <span data-feather="file-text" data-toggle="tooltip" data-placement="top" title="{{__('text.dineroVocaliasDesc')}}">
            {{__('text.dineroVocalias')}}
            </span>
          </a>
        </li>
        <li class="nav-item">
          <div data-toggle="modal" data-target="#moratoriaCuotas_modal" onclick="colapsardiasAplazamiento();">
            <a class="nav-link" href="#">
              <span data-feather="file-text" data-toggle="tooltip" data-placement="top" title="{{__('text.moratoriaCuotasDesc')}}">
              {{__('text.moratoriaCuotas')}}
              </span>
            </a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
