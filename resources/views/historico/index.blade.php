@extends('layouts.master')

@section('title', 'Checklist de Produção - Sistema')

@section('styles')
    <link rel="icon" href="{{ asset('images/promova.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .badge-completed {
            background-color: #28a745;
            color: white;
        }

        .badge-in-progress {
            background-color: #ffc107;
            color: #000;
        }

        .progress-bar-custom {
            height: 8px;
            border-radius: 4px;
        }

        .action-buttons .btn {
            margin-right: 5px;
        }

        /* ======= Estilos Gerais ======= */
        body {
            font-family: "Poppins", sans-serif;
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 20px;
        }

        .container-central {
            width: 100%;
            max-width: 1100px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ======= Campo de Busca ======= */
        .filtered-list-search {
            width: 100%;
            max-width: 800px;
            text-align: center;
            margin: 1.5rem auto;
        }

        .form-inline {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .product-search {
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 15px;
            border: 1px solid #ced4da;
            width: 100%;
            transition: all 0.3s ease;
        }

        .product-search:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
            outline: none;
        }

        button.btn.btn-primary {
            margin-left: 110px;
            */ border-radius: 30px;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
        }

        button.btn.btn-primary:hover {
            background-color: #0056b3;
        }

        /* ======= Tabela ======= */
        .table-responsive {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            text-align: center;
        }

        .table {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin: 0 auto;
        }

        .table thead {
            background: #f1f3f5;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
            padding: 12px;
            text-align: center;
        }

        .table-hover tbody tr:hover {
            background-color: #f9fbfd;
        }

        /* ======= Checkboxes ======= */
        .form-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        /* ======= Badges ======= */
        .badge {
            border-radius: 12px;
            padding: 6px 10px;
            font-size: 13px;
        }

        .badge-info {
            background: #17a2b8;
            color: #fff;
        }

        .badge-completed {
            background: #28a745;
            color: #fff;
        }

        .badge-in-progress {
            background: #ffc107;
            color: #333;
        }

        /* ======= Progress Bar ======= */
        .progress-bar-custom {
            height: 8px;
            border-radius: 5px;
            background-color: #e9ecef;
            overflow: hidden;
            margin-bottom: 4px;
        }

        .progress-bar {
            transition: width 0.4s ease;
        }

        /* ======= Botões de Ação ======= */
        .action-buttons {
            display: flex;
            gap: 6px;
            justify-content: center;
        }

        .action-buttons .btn {
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .action-buttons .btn-info:hover {
            background-color: #0dcaf0;
        }

        .action-buttons .btn-success:hover {
            background-color: #198754;
        }

        .action-buttons .btn-danger:hover {
            background-color: #dc3545;
        }

        /* ======= Responsividade ======= */
        @media (max-width: 992px) {
            .table-responsive {
                border: none;
                box-shadow: none;
            }

            .table th,
            .table td {
                font-size: 14px;
                padding: 10px;
            }
        }

        @media (max-width: 768px) {
            .container-central {
                padding: 0 10px;
            }

            .filtered-list-search {
                width: 100%;
                padding: 0 10px;
            }

            .product-search {
                font-size: 14px;
            }

            button.btn.btn-primary {
                padding: 6px 12px;
            }

            .action-buttons {
                flex-wrap: wrap;
                gap: 4px;
            }

            .badge {
                font-size: 12px;
                padding: 5px 8px;
            }
        }

        @media (max-width: 576px) {
            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 1rem;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
                padding: 10px;
            }

            .table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border: none;
            }

            .table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #555;
            }
        }

        /* ======= NAVBAR PRINCIPAL ======= */
        .navbar {
            background-color: #ffffff;
            border-bottom: 2px solid #e9ecef;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            padding: 12px 20px;
            position: sticky;
            top: 0;
            z-index: 999;
            width: 100%;
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* ======= LOGO E TÍTULO ======= */
        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 10px;
        }

        .navbar-logo {
            height: 45px;
            width: auto;
            border-radius: 8px;
        }

        .navbar-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }

        /* ======= LINKS DE NAVEGAÇÃO ======= */
        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #555;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: #f1f3f5;
            color: #007bff;
        }

        .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }

        .nav-link .material-icons {
            font-size: 20px;
            vertical-align: middle;
        }

        /* ======= BOTÃO HAMBÚRGUER (MOBILE) ======= */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 28px;
            color: #333;
        }

        .menu-toggle:focus {
            outline: none;
        }

        /* ======= RESPONSIVIDADE ======= */
        @media (max-width: 768px) {
            .navbar-container {
                flex-wrap: wrap;
            }

            .menu-toggle {
                display: block;
            }

            .navbar-menu {
                display: none;
                width: 100%;
                flex-direction: column;
                gap: 10px;
                margin-top: 12px;
                background-color: #fff;
                border-top: 1px solid #e9ecef;
                padding-top: 10px;
                animation: slideDown 0.3s ease;
            }

            .navbar-menu.show {
                display: flex;
            }

            .nav-link {
                justify-content: center;
                font-size: 1rem;
            }

            .navbar-logo {
                height: 40px;
            }

            .navbar-title {
                font-size: 1rem;
            }
        }

        /* ======= ANIMAÇÃO ======= */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@section('content')


    <div class="container-central">


        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-container">
                <a href="{{ route('dashboard') }}" class="navbar-brand">
                    <img src="{{ asset('images/promova.jpg') }}" alt="Promova" class="navbar-logo">
                    <span class="navbar-title">Sistema Promova</span>
                </a>

                <!-- Botão hambúrguer -->
                <button class="menu-toggle" aria-label="Abrir menu">
                    <span class="material-icons">menu</span>
                </button>

                <div class="navbar-menu">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <span class="material-icons">dashboard</span>
                        Dashboard
                    </a>

                    <a href="{{ route('checklist') }}" class="nav-link">
                        <span class="material-icons">checklist</span>
                        Checklist
                    </a>

                    <a href="{{ route('historico') }}" class="nav-link active">
                        <span class="material-icons">history</span>
                        Histórico
                    </a>

                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="nav-link">
                        <span class="material-icons">logout</span>
                        Sair
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </nav>

        <div class="col-lg-8 col-md-8 col-sm-9 filtered-list-search mx-auto mb-3">
            <form class="form-inline my-2 my-lg-0 justify-content-center">
                <div class="w-100">
                    <input type="text" class="w-100 form-control product-search br-30" id="input-search"
                        placeholder="Pesquisar por nome do job ou data..." name="pesquisar"
                        value="{{ Request::get('pesquisar') ? Request::get('pesquisar') : '' }}">
                    {{-- <button class="btn btn-primary" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" 
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                         class="feather feather-search">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button> --}}
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col" width="5%">
                            <div class="form-check form-check-primary">
                                <input class="form-check-input" id="custom_mixed_parent_all" type="checkbox">
                            </div>
                        </th>
                        <th scope="col">Nome do Job</th>
                        <th scope="col" class="text-center">Data</th>
                        <th scope="col" class="text-center">Usuário</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center">Ações</th>
                    </tr>
                    <tr aria-hidden="true" class="mt-3 d-block table-row-hidden"></tr>
                </thead>

                <tbody>
                    @forelse ($historicos as $checklist)
                        <tr>
                            <td>
                                <div class="form-check form-check-primary">
                                    <input class="form-check-input custom_mixed_child" type="checkbox"
                                        value="{{ $checklist->id }}">
                                </div>
                            </td>

                            <td>
                                <div class="media-body align-self-center">
                                    <h6 class="mb-0">{{ $checklist->job_name }}</h6>
                                    @if ($checklist->completed_at)
                                        <small class="text-muted">
                                            Concluído em: {{ $checklist->completed_at->format('d/m/Y H:i') }}
                                        </small>
                                    @endif
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="media-body align-self-center">
                                    <h6 class="mb-0">{{ \Carbon\Carbon::parse($checklist->job_date)->format('d/m/Y') }}</h6>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="media-body align-self-center">
                                    <span class="badge badge-info">
                                        {{ $checklist->user->name ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>

                            <td class="text-center">
                                @if ($checklist->is_completed)
                                    <span class="badge badge-completed">Concluído</span>
                                @else
                                    <span class="badge badge-in-progress">Em Andamento</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="action-buttons">
                                    <a href="{{ route('historico.show', $checklist->id) }}" class="btn btn-sm btn-info"
                                        title="Visualizar">
                                        <i class="material-icons">visibility</i>
                                    </a>


                                    @if ($checklist->pdf_url)
                                        <a href="{{ $checklist->pdf_url }}" class="btn btn-sm btn-success" target="_blank"
                                            title="Download PDF">
                                            <i class="material-icons">picture_as_pdf</i>
                                        </a>
                                    @endif

                                    {{-- <form action="{{ route('checklist.destroy', $checklist->id) }}" 
                                      method="POST" 
                                      style="display: inline;"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este checklist?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger" 
                                            title="Excluir">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </form> --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <p class="text-muted my-3">Nenhum checklist encontrado.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $historicos->links() }}
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Checkbox "Selecionar todos"
        document.getElementById('custom_mixed_parent_all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.custom_mixed_child');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Atualizar checkbox principal baseado nos filhos
        document.querySelectorAll('.custom_mixed_child').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allCheckboxes = document.querySelectorAll('.custom_mixed_child');
                const checkedCheckboxes = document.querySelectorAll('.custom_mixed_child:checked');
                const parentCheckbox = document.getElementById('custom_mixed_parent_all');

                parentCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.querySelector(".menu-toggle");
            const menu = document.querySelector(".navbar-menu");
            toggle.addEventListener("click", () => {
                menu.classList.toggle("show");
            });
        });
    </script>
@endsection
