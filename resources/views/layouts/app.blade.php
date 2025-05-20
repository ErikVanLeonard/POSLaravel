<!DOCTYPE html>
<html lang="es" data-theme="light-dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - POS Laravel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    @stack('styles')
    <style>
        @media (prefers-color-scheme: dark) {
            html[data-theme="light-dark"] {
                background-color: #0a0a0a;
                color: #fff;
            }
            .title, .label, strong, th {
                color: #fff !important;
            }
            .box {
                background-color: #1a1a1a;
                color: #fff;
            }
            .menu-list a {
                color: #fff;
            }
            .menu-list a:hover {
                background-color: #363636;
            }
            .menu-list a.is-active {
                background-color: #485fc7;
            }
            .input {
                background-color: #242424;
                color: #fff;
                border-color: #363636;
            }
            .input:focus {
                border-color: #485fc7;
            }
            .help.is-danger {
                color: #ff6b6b;
            }
            .table {
                background-color: #1a1a1a;
                color: #fff;
            }
            .table.is-striped tbody tr:not(.is-selected):nth-child(even) {
                background-color: #242424;
            }
            .notification.is-success.is-light {
                background-color: #0d3a1f;
                color: #90edb3;
            }
        }

        .main-content {
            margin-right: 250px;
            transition: margin-right 0.3s;
        }

        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 250px;
            background-color: #1a1a1a;
            padding: 1.5rem;
            overflow-y: auto;
        }

        @media screen and (max-width: 768px) {
            .sidebar {
                transform: translateX(100%);
                transition: transform 0.3s;
            }

            .sidebar.is-active {
                transform: translateX(0);
            }

            .main-content {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="main-content">
        @yield('content')
    </div>

    <aside class="sidebar">
        <div class="menu">
            <p class="menu-label has-text-grey-lighter">
                Navegación
            </p>
            <ul class="menu-list">
                <li><a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'is-active' : '' }}">
                    Productos
                </a></li>
            </ul>

            <p class="menu-label has-text-grey-lighter mt-6">
                Cuenta
            </p>
            <ul class="menu-list">
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="button is-text has-text-danger is-fullwidth has-text-left" style="text-decoration: none;">
                            Cerrar Sesión
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </aside>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Funcionalidad para dispositivos móviles
            const burger = document.createElement('button');
            burger.classList.add('button', 'is-primary', 'is-fixed');
            burger.style.position = 'fixed';
            burger.style.bottom = '20px';
            burger.style.right = '20px';
            burger.style.zIndex = '1000';
            burger.style.display = 'none';
            burger.innerHTML = '☰';

            document.body.appendChild(burger);

            const sidebar = document.querySelector('.sidebar');

            // Mostrar/ocultar el botón según el tamaño de la pantalla
            function checkScreenSize() {
                if (window.innerWidth <= 768) {
                    burger.style.display = 'block';
                } else {
                    burger.style.display = 'none';
                    sidebar.classList.remove('is-active');
                }
            }

            window.addEventListener('resize', checkScreenSize);
            checkScreenSize(); // Verificar al cargar

            burger.addEventListener('click', () => {
                sidebar.classList.toggle('is-active');
            });

            // Cerrar sidebar al hacer clic fuera de él en móvil
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768 &&
                    !sidebar.contains(e.target) &&
                    !burger.contains(e.target) &&
                    sidebar.classList.contains('is-active')) {
                    sidebar.classList.remove('is-active');
                }
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    @stack('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
