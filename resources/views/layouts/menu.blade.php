<li class="side-menus {{ Request::is('*') ? 'active' : '' }}">
    <a class="nav-link" href="/home">
        <i class="fas fa-building"></i><span>Home</span>
    </a>
    @can('ver-usuario')
    <a class="nav-link" href="/usuarios">
        <i class="fas fa-users"></i><span>Usuarios</span>
    </a>
    @endcan
    @can('ver-rol')
    <a class="nav-link" href="/roles">
        <i class="fas fa-user-lock"></i><span>Roles</span>
    </a>
    @endcan
    @can('ver-cliente')
    <a class="nav-link" href="/clientes">
        <i class="fas fa-pen"></i><span>Socios</span>
    </a>
    @endcan
    @can('ver-cliente')
    <a class="nav-link" href="/prestamos">
        <i class="fas fa-eraser"></i><span>Prestamos</span>
    </a>
    @endcan
    @can('ver-cliente')
    <a class="nav-link" href="/pagos">
        <i class="fas fa-newspaper"></i><span>Pago</span>
    </a>
    @endcan
    <a class="nav-link" href="{{ route('cortes.resumen') }}">
        <i class="fas fa-chart-line"></i><span>Corte Diario</span>
    </a>
    <a class="nav-link"  href="{{ route('corte.semanal') }}">
        <i class="fas fa-chart-line"></i><span>Corte Semanal</span>
    </a>
    <a class="nav-link" href="{{ route('cotizacion.index') }}">
        <i class="fas fa-pen"></i><span>Cotización</span>
    </a>
</li>
