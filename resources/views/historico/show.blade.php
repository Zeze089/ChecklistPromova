@extends('layouts.master')

@section('title', 'Visualizar Checklist - ' . $checklist->job_name)

@section('styles')
    <link rel="icon" href="{{ asset('images/promova.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* ======= Alerta de Sucesso ======= */
        .alert-success {
            background: #e6f9ed;
            border: 1px solid #b6e6c3;
            border-radius: 16px;
            color: #198754;
            padding: 25px 20px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            animation: fadeIn 0.6s ease-in-out;
        }

        .alert-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }

        /* Ícone de sucesso */
        .alert-success i.material-icons {
            color: #28a745;
            font-size: 2.8rem !important;
            margin-bottom: 10px;
        }

        /* Título */
        .alert-success h5 {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 8px 0;
            color: #155724;
        }

        /* Texto secundário */
        .alert-success p {
            font-size: 0.95rem;
            color: #1c7430;
            margin-top: 4px;
        }

        /* ======= Animação suave ======= */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ======= Responsividade ======= */
        @media (max-width: 576px) {
            .alert-success {
                padding: 20px 15px;
                border-radius: 12px;
            }

            .alert-success h5 {
                font-size: 1.2rem;
            }

            .alert-success p {
                font-size: 0.9rem;
            }

            .alert-success i.material-icons {
                font-size: 2.2rem !important;
            }
        }


        .checklist-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .info-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .category-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .category-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #667eea;
        }

        .item-row {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-row:hover {
            background-color: #f8f9fa;
        }

        .item-name {
            flex: 2;
            font-weight: 500;
        }

        .item-quantity {
            flex: 1;
            text-align: center;
        }

        .item-checks {
            flex: 1;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .check-box {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .check-icon {
            font-size: 20px;
        }

        .check-icon.checked {
            color: #28a745;
        }

        .check-icon.unchecked {
            color: #dc3545;
        }

        .item-observations {
            flex: 2;
            font-style: italic;
            color: #6c757d;
        }

        .progress-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            margin: 0 auto;
        }

        .progress-complete {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .progress-incomplete {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .badge-custom {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .empty-category {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
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

    <div class="container-fluid">
        <!-- Header -->
        <div class="checklist-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">{{ $checklist->job_name }}</h2>
                    <p class="mb-0">
                        <i class="material-icons" style="vertical-align: middle;">event</i>
                        Data: {{ $checklist->job_date }}
                    </p>
                </div>
                <div class="col-md-4 text-right">
                    @if ($checklist->is_completed)
                        <span class="badge bg-success">Concluído</span>
                    @else
                        <span class="badge bg-danger">Não concluído</span>
                    @endif

                </div>
            </div>
        </div>

        <!-- Informações Gerais -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="info-card text-center">
                    <i class="material-icons" style="font-size: 2rem; color: #667eea;">person</i>
                    <h6 class="mt-2 mb-1">Responsável</h6>
                    <p class="mb-0">{{ $checklist->user->name ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-card text-center">
                    <i class="material-icons" style="font-size: 2rem; color: #667eea;">date_range</i>
                    <h6 class="mt-2 mb-1">Criado em</h6>
                    <p class="mb-0">{{ $checklist->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-card text-center">
                    <i class="material-icons" style="font-size: 2rem; color: #667eea;">
                        {{ $checklist->is_completed ? 'check_circle' : 'hourglass_empty' }}
                    </i>
                    <h6 class="mt-2 mb-1">Status</h6>
                    <p class="mb-0">
                        <span
                            class="badge badge-custom {{ $checklist->is_completed ? 'badge-success' : 'badge-warning' }}">
                            {{ $checklist->is_completed ? 'Concluído' : 'Em Andamento' }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-card text-center">
                    <i class="material-icons" style="font-size: 2rem; color: #667eea;">update</i>
                    <h6 class="mt-2 mb-1">Última Atualização</h6>
                    <p class="mb-0">{{ $checklist->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="action-buttons mb-4">
            <a href="{{ route('historico') }}" class="btn btn-secondary">
                <i class="material-icons" style="vertical-align: middle;">arrow_back</i>
                Voltar
            </a>

            {{-- @if (!$checklist->is_completed)
                <a href="{{ route('checklist.edit', $checklist->id) }}" class="btn btn-warning">
                    <i class="material-icons" style="vertical-align: middle;">edit</i>
                    Editar
                </a>
            @endif --}}

            {{--  @if ($checklist->pdf_url)
                <a href="{{ $checklist->pdf_url }}" class="btn btn-success" target="_blank">
                    <i class="material-icons" style="vertical-align: middle;">picture_as_pdf</i>
                    Download PDF
                </a>
            @else
                <a href="{{ route('checklist.generate-pdf', $checklist->id) }}" class="btn btn-success">
                    <i class="material-icons" style="vertical-align: middle;">picture_as_pdf</i>
                    Gerar PDF
                </a>
            @endif --}}

            {{-- <form action="{{ route('checklist.destroy', $checklist->id) }}" 
                  method="POST" 
                  style="display: inline;"
                  onsubmit="return confirm('Tem certeza que deseja excluir este checklist?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="material-icons" style="vertical-align: middle;">delete</i>
                    Excluir
                </button>
            </form> --}}
        </div>

        <!-- Itens do Checklist por Categoria -->
        @php
            $organizedData = $checklist->organized_data;
        @endphp

        @foreach ($organizedData as $category => $items)
            <div class="category-section">
                <h4 class="category-title">
                    <i class="material-icons" style="vertical-align: middle;">
                        @switch($category)
                            @case('Câmera')
                                camera_alt
                            @break

                            @case('Lentes')
                                camera
                            @break

                            @case('Iluminação')
                                wb_incandescent
                            @break

                            @case('Som')
                                mic
                            @break

                            @case('Estabilização')
                                videocam
                            @break

                            @default
                                devices_other
                        @endswitch
                    </i>
                    {{ $category }}
                </h4>

                @if (count($items) > 0)
                    <div class="items-list">
                        <!-- Cabeçalho -->
                        <div class="item-row" style="background-color: #f8f9fa; font-weight: 600;">
                            <div class="item-name">Item</div>
                            <div class="item-quantity">Quantidade</div>
                            <div class="item-checks">
                                <div class="check-box">Ida</div>
                                <div class="check-box">Volta</div>
                            </div>
                            <div class="item-observations">Observações</div>
                        </div>

                        <!-- Itens -->
                        @foreach ($items as $item)
                            <div class="item-row">
                                <div class="item-name">
                                    {{ $item['name'] }}
                                    @if ($item['is_editable'])
                                        <span class="badge badge-info badge-sm ml-2">Personalizado</span>
                                    @endif
                                </div>
                                <div class="item-quantity">
                                    <strong>{{ $item['quantity'] ?: '-' }}</strong>
                                </div>
                                <div class="item-checks">
                                    <div class="check-box">
                                        <i class="material-icons check-icon {{ $item['ida'] ? 'checked' : 'unchecked' }}">
                                            {{ $item['ida'] ? 'check_circle' : 'cancel' }}
                                        </i>
                                    </div>
                                    <div class="check-box">
                                        <i
                                            class="material-icons check-icon {{ $item['volta'] ? 'checked' : 'unchecked' }}">
                                            {{ $item['volta'] ? 'check_circle' : 'cancel' }}
                                        </i>
                                    </div>
                                </div>
                                <div class="item-observations">
                                    {{ $item['observations'] ?: 'Sem observações' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-category">
                        <i class="material-icons" style="font-size: 3rem; opacity: 0.3;">inventory_2</i>
                        <p class="mb-0">Nenhum item nesta categoria</p>
                    </div>
                @endif
            </div>
        @endforeach

        @if ($checklist->completed_at)
            <div class="alert alert-success text-center" role="alert">
                <i class="material-icons" style="vertical-align: middle; font-size: 2rem;">check_circle_outline</i>
                <h5 class="mt-2">Checklist Concluído</h5>
                <p class="mb-0">Finalizado em {{ $checklist->completed_at->format('d/m/Y \à\s H:i') }}</p>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        // Confirmação antes de excluir
        document.querySelectorAll('form[onsubmit]').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm(
                        'Tem certeza que deseja excluir este checklist? Esta ação não pode ser desfeita.'
                    )) {
                    e.preventDefault();
                }
            });
        });

        // Animação de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.category-section');
            sections.forEach((section, index) => {
                setTimeout(() => {
                    section.style.opacity = '0';
                    section.style.transform = 'translateY(20px)';
                    section.style.transition = 'all 0.3s ease';

                    setTimeout(() => {
                        section.style.opacity = '1';
                        section.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
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
