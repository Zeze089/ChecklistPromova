@extends('layouts.master')

@section('title', 'Visualizar Checklist - ' . $checklist->job_name)

@section('styles')
    <link rel="icon" href="{{ asset('images/promova.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-light: #3b82f6;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-500: #64748b;
            --gray-700: #334155;
            --gray-900: #0f172a;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--gray-50) 0%, #e0f2fe 100%);
            min-height: 100vh;
        }

        .container-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* ======= HEADER DO CHECKLIST ======= */
        .checklist-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 2.5rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(30, 58, 138, 0.3);
            position: relative;
            overflow: hidden;
        }

        .checklist-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .checklist-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .header-info h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .header-info h1 .material-icons {
            font-size: 2.5rem;
            opacity: 0.9;
        }

        .header-meta {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .meta-item .material-icons {
            font-size: 1.2rem;
        }

        .status-badge {
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .status-badge.completed {
            background: var(--success);
            color: white;
        }

        .status-badge.in-progress {
            background: var(--warning);
            color: #1a1a1a;
        }

        /* ======= CARDS DE ESTATÍSTICAS ======= */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
            border: 1px solid var(--gray-100);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon .material-icons {
            font-size: 1.75rem;
            color: white;
        }

        .stat-icon.blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .stat-icon.green { background: linear-gradient(135deg, #22c55e, #16a34a); }
        .stat-icon.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

        .stat-content h3 {
            font-size: 0.85rem;
            color: var(--gray-500);
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .stat-content p {
            font-size: 1.1rem;
            color: var(--gray-900);
            font-weight: 600;
            margin: 0;
        }

        /* ======= BOTÕES DE AÇÃO ======= */
        .action-bar {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .btn-action {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-action .material-icons {
            font-size: 1.25rem;
        }

        .btn-back {
            background: var(--gray-100);
            color: var(--gray-700);
        }

        .btn-back:hover {
            background: var(--gray-200);
            color: var(--gray-900);
        }

        .btn-edit {
            background: linear-gradient(135deg, var(--warning), #d97706);
            color: white;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        }

        .btn-pdf {
            background: linear-gradient(135deg, var(--danger), #dc2626);
            color: white;
        }

        .btn-pdf:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
        }

        /* ======= SEÇÕES DE CATEGORIA ======= */
        .category-section {
            background: white;
            border-radius: 20px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid var(--gray-100);
        }

        .category-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .category-header .material-icons {
            font-size: 1.75rem;
        }

        .category-header h4 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .category-content {
            padding: 0;
        }

        /* ======= TABELA DE ITENS ======= */
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table thead {
            background: var(--gray-50);
        }

        .items-table th {
            padding: 1rem 1.25rem;
            text-align: left;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--gray-100);
        }

        .items-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .items-table tbody tr:last-child td {
            border-bottom: none;
        }

        .items-table tbody tr:hover {
            background: var(--gray-50);
        }

        .item-name-cell {
            font-weight: 500;
            color: var(--gray-900);
        }

        .badge-custom {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #dbeafe;
            color: #1e40af;
            margin-left: 0.5rem;
        }

        .quantity-cell {
            text-align: center;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--primary);
        }

        .check-cell {
            text-align: center;
        }

        .check-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }

        .check-icon.success {
            background: #dcfce7;
            color: var(--success);
        }

        .check-icon.danger {
            background: #fee2e2;
            color: var(--danger);
        }

        .check-icon .material-icons {
            font-size: 1.25rem;
        }

        .obs-cell {
            color: var(--gray-500);
            font-style: italic;
            max-width: 250px;
        }

        /* ======= CATEGORIA VAZIA ======= */
        .empty-category {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--gray-500);
        }

        .empty-category .material-icons {
            font-size: 4rem;
            opacity: 0.3;
            margin-bottom: 1rem;
        }

        /* ======= ALERTA DE CONCLUSÃO ======= */
        .completion-alert {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border: 2px solid var(--success);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            margin-top: 2rem;
        }

        .completion-alert .material-icons {
            font-size: 3.5rem;
            color: var(--success);
            margin-bottom: 1rem;
        }

        .completion-alert h3 {
            color: #166534;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .completion-alert p {
            color: #15803d;
            font-size: 1rem;
            margin: 0;
        }

        /* ======= NAVBAR ======= */
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
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 28px;
            color: #333;
        }

        /* ======= RESPONSIVIDADE ======= */
        @media (max-width: 992px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .items-table {
                font-size: 0.9rem;
            }

            .items-table th,
            .items-table td {
                padding: 0.75rem 1rem;
            }
        }

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
                border-top: 1px solid #e9ecef;
                padding-top: 10px;
            }

            .navbar-menu.show {
                display: flex;
            }

            .checklist-header {
                padding: 1.5rem;
            }

            .header-info h1 {
                font-size: 1.5rem;
            }

            .header-meta {
                gap: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .action-bar {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }

            /* Tabela responsiva */
            .items-table thead {
                display: none;
            }

            .items-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                padding: 1rem;
            }

            .items-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
                border-bottom: 1px solid var(--gray-100);
            }

            .items-table td:last-child {
                border-bottom: none;
            }

            .items-table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--gray-500);
                font-size: 0.8rem;
                text-transform: uppercase;
            }
        }

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
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
                    <span class="material-icons">logout</span>
                    Sair
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            </div>
        </div>
    </nav>

    <div class="container-main">
        <!-- Header do Checklist -->
        <div class="checklist-header">
            <div class="header-content">
                <div class="header-info">
                    <h1>
                        <span class="material-icons">assignment</span>
                        {{ $checklist->job_name }}
                    </h1>
                    <div class="header-meta">
                        <div class="meta-item">
                            <span class="material-icons">event</span>
                            {{ \Carbon\Carbon::parse($checklist->job_date)->format('d/m/Y') }}
                        </div>
                        <div class="meta-item">
                            <span class="material-icons">person</span>
                            {{ $checklist->user->name ?? 'N/A' }}
                        </div>
                        <div class="meta-item">
                            <span class="material-icons">schedule</span>
                            Criado em {{ $checklist->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
                <div class="status-badge {{ $checklist->is_completed ? 'completed' : 'in-progress' }}">
                    <span class="material-icons">
                        {{ $checklist->is_completed ? 'check_circle' : 'hourglass_empty' }}
                    </span>
                    {{ $checklist->is_completed ? 'Concluído' : 'Em Andamento' }}
                </div>
            </div>
        </div>

        <!-- Cards de Estatísticas -->
        @php
            $organizedData = $checklist->organized_data;
            $totalItems = 0;
            $totalCheckedIda = 0;
            $totalCheckedVolta = 0;

            foreach ($organizedData as $items) {
                $totalItems += count($items);
                foreach ($items as $item) {
                    if ($item['ida']) $totalCheckedIda++;
                    if ($item['volta']) $totalCheckedVolta++;
                }
            }

            $totalChecks = $totalItems * 2;
            $completedChecks = $totalCheckedIda + $totalCheckedVolta;
            $progressPercent = $totalChecks > 0 ? round(($completedChecks / $totalChecks) * 100) : 0;
        @endphp

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <span class="material-icons">inventory_2</span>
                </div>
                <div class="stat-content">
                    <h3>Total de Itens</h3>
                    <p>{{ $totalItems }} itens</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <span class="material-icons">trending_up</span>
                </div>
                <div class="stat-content">
                    <h3>Progresso</h3>
                    <p>{{ $progressPercent }}% completo</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <span class="material-icons">flight_takeoff</span>
                </div>
                <div class="stat-content">
                    <h3>Check Ida</h3>
                    <p>{{ $totalCheckedIda }}/{{ $totalItems }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <span class="material-icons">flight_land</span>
                </div>
                <div class="stat-content">
                    <h3>Check Volta</h3>
                    <p>{{ $totalCheckedVolta }}/{{ $totalItems }}</p>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="action-bar">
            <a href="{{ route('historico') }}" class="btn-action btn-back">
                <span class="material-icons">arrow_back</span>
                Voltar ao Histórico
            </a>

            @if (!$checklist->is_completed)
                <a href="{{ route('checklist.edit', $checklist->id) }}" class="btn-action btn-edit">
                    <span class="material-icons">edit</span>
                    Continuar Editando
                </a>
            @endif

            @if ($checklist->pdf_url)
                <a href="{{ $checklist->pdf_url }}" class="btn-action btn-pdf" target="_blank">
                    <span class="material-icons">picture_as_pdf</span>
                    Download PDF
                </a>
            @endif
        </div>

        <!-- Categorias e Itens -->
        @php
            $categoryIcons = [
                'Câmera' => 'camera_alt',
                'Lentes' => 'camera',
                'Iluminação' => 'wb_incandescent',
                'Som' => 'mic',
                'Estabilização' => 'videocam',
                'Extras' => 'devices_other'
            ];
        @endphp

        @foreach ($organizedData as $category => $items)
            <div class="category-section">
                <div class="category-header">
                    <span class="material-icons">{{ $categoryIcons[$category] ?? 'folder' }}</span>
                    <h4>{{ $category }}</h4>
                </div>

                <div class="category-content">
                    @if (count($items) > 0)
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th style="width: 35%;">Item</th>
                                    <th style="width: 10%; text-align: center;">Qtd</th>
                                    <th style="width: 10%; text-align: center;">Ida</th>
                                    <th style="width: 10%; text-align: center;">Volta</th>
                                    <th style="width: 35%;">Observações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="item-name-cell" data-label="Item">
                                            {{ $item['name'] }}
                                            @if ($item['is_editable'])
                                                <span class="badge-custom">
                                                    <span class="material-icons" style="font-size: 0.75rem;">edit</span>
                                                    Personalizado
                                                </span>
                                            @endif
                                        </td>
                                        <td class="quantity-cell" data-label="Quantidade">
                                            {{ $item['quantity'] ?: '-' }}
                                        </td>
                                        <td class="check-cell" data-label="Ida">
                                            <span class="check-icon {{ $item['ida'] ? 'success' : 'danger' }}">
                                                <span class="material-icons">
                                                    {{ $item['ida'] ? 'check' : 'close' }}
                                                </span>
                                            </span>
                                        </td>
                                        <td class="check-cell" data-label="Volta">
                                            <span class="check-icon {{ $item['volta'] ? 'success' : 'danger' }}">
                                                <span class="material-icons">
                                                    {{ $item['volta'] ? 'check' : 'close' }}
                                                </span>
                                            </span>
                                        </td>
                                        <td class="obs-cell" data-label="Observações">
                                            {{ $item['observations'] ?: '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-category">
                            <span class="material-icons">inventory_2</span>
                            <p>Nenhum item nesta categoria</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- Alerta de Conclusão -->
        @if ($checklist->is_completed && $checklist->completed_at)
            <div class="completion-alert">
                <span class="material-icons">verified</span>
                <h3>Checklist Concluído com Sucesso!</h3>
                <p>Finalizado em {{ $checklist->completed_at->format('d/m/Y \à\s H:i') }}</p>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Toggle menu mobile
            const toggle = document.querySelector(".menu-toggle");
            const menu = document.querySelector(".navbar-menu");
            toggle.addEventListener("click", () => {
                menu.classList.toggle("show");
            });

            // Animação de entrada das categorias
            const sections = document.querySelectorAll('.category-section');
            sections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    section.style.transition = 'all 0.4s ease';
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, 100 + (index * 100));
            });
        });
    </script>
@endsection
