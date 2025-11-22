@extends('layouts.master')


@section('title', 'Checklist - Sistema Promova')

@section('styles')
    <link rel="icon" href="{{ asset('images/promova.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Reset e Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e3e7f1 100%);
            min-height: 100vh;
        }

        /* ==================== NAVBAR ==================== */
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

        /* Logo e Título */
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

        /* Links de Navegação */
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
            background-color: #667eea;
            color: #fff;
        }

        .nav-link .material-icons {
            font-size: 20px;
            vertical-align: middle;
        }

        /* Botão Hambúrguer */
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

        /* ==================== DASHBOARD CONTAINER ==================== */
        .dashboard-container {
            max-width: 1400px;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        /* Seção de Boas-Vindas */
        .welcome-section {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            margin-bottom: 3rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1rem;
            text-align: center;
        }

        .welcome-subtitle {
            font-size: 1.2rem;
            color: #718096;
            text-align: center;
        }

        /* ==================== TABELA ==================== */
        .table-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .checklist-table {
            width: 100%;
            border-collapse: collapse;
        }

        .checklist-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .checklist-table th {
            padding: 1.2rem 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.95rem;
            white-space: nowrap;
        }

        .checklist-table th .material-icons {
            vertical-align: middle;
            font-size: 1.2rem;
            margin-right: 0.3rem;
        }

        .checklist-table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .checklist-table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }

        .checklist-table td {
            padding: 1.2rem 1rem;
            color: #2d3748;
        }

        /* Células da Tabela */
        .job-name {
            font-weight: 600;
            color: #2d3748;
            font-size: 1rem;
        }

        .job-date {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #667eea;
            font-weight: 500;
        }

        .job-date .material-icons {
            font-size: 1.1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #718096;
        }

        .user-info .material-icons {
            font-size: 1.2rem;
            color: #667eea;
        }

        .timestamp {
            color: #718096;
            font-size: 0.9rem;
        }

        /* Botão PDF */
        .btn-pdf {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-pdf:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 233, 123, 0.4);
        }

        .btn-pdf .material-icons {
            font-size: 1.1rem;
        }

        .no-pdf {
            color: #cbd5e0;
            font-style: italic;
            font-size: 0.9rem;
        }

        /* Botões de Ação */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            text-decoration: none;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
        }

        .btn-delete {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 87, 108, 0.4);
        }

        .btn-action .material-icons {
            font-size: 1.2rem;
        }

        /* ==================== EMPTY STATE ==================== */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #718096;
        }

        .empty-state .material-icons {
            font-size: 5rem;
            color: #cbd5e0;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            margin-bottom: 2rem;
        }

        .btn-create {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-create:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-create .material-icons {
            font-size: 1.3rem;
        }

        /* ==================== PAGINAÇÃO ==================== */
        .pagination-container {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        /* ==================== ANIMAÇÕES ==================== */
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

        /* ==================== RESPONSIVIDADE ==================== */

        /* Tablets e dispositivos médios */
        @media (max-width: 992px) {
            .table-container {
                padding: 1rem;
            }

            .checklist-table {
                font-size: 0.85rem;
            }

            .checklist-table th,
            .checklist-table td {
                padding: 0.8rem 0.5rem;
            }
        }

        /* Mobile */
        @media (max-width: 768px) {

            /* Navbar Mobile */
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

            /* Dashboard Mobile */
            .dashboard-container {
                padding: 0 1rem;
                margin: 2rem auto;
            }

            .welcome-section {
                padding: 2rem 1.5rem;
            }

            .welcome-title {
                font-size: 1.8rem;
            }

            .welcome-subtitle {
                font-size: 1rem;
            }

            /* Tabela Mobile */
            .action-buttons {
                flex-direction: column;
            }

            .btn-pdf {
                padding: 0.5rem 0.8rem;
                font-size: 0.85rem;
            }
        }

        /* Mobile Extra Pequeno */
        @media (max-width: 480px) {
            .welcome-title {
                font-size: 1.5rem;
            }

            .welcome-subtitle {
                font-size: 0.9rem;
            }

            .checklist-table th,
            .checklist-table td {
                padding: 0.6rem 0.3rem;
                font-size: 0.75rem;
            }

            .btn-action {
                width: 32px;
                height: 32px;
            }

            .btn-action .material-icons {
                font-size: 1rem;
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
                <a href="{{ route('dashboard') }}" class="nav-link active">
                    <span class="material-icons">dashboard</span>
                    Dashboard
                </a>

                <a href="{{ route('checklist.create') }}" class="nav-link">
                    <span class="material-icons">checklist</span>
                    Novo Checklist
                </a>

                <a href="{{ route('historico') }}" class="nav-link">
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

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Container Principal -->
    <div class="dashboard-container">
        <!-- Cabeçalho da Página -->
        <div class="welcome-section">
            <h1 class="welcome-title">
                <span class="material-icons"
                    style="vertical-align: middle; font-size: 2.5rem; color: #667eea;">checklist</span>
                Checklists Salvos
            </h1>
            <p class="welcome-subtitle">Gerencie e visualize todos os checklists não concluidos</p>
        </div>

        <!-- Tabela de Checklists -->
        <div class="table-container">
            @if ($listas->count() > 0)
                <div class="table-responsive">
                    <table class="checklist-table">
                        <thead>
                            <tr>
                                <th><span class="material-icons">work</span> Job</th>
                                <th><span class="material-icons">event</span> Data</th>
                                <th><span class="material-icons">person</span> Responsável</th>
                                <th><span class="material-icons">check_circle</span> Status</th>
                                <th><span class="material-icons">schedule</span> Salvo em</th>
                                <th><span class="material-icons">picture_as_pdf</span> PDF</th>
                                <th><span class="material-icons">settings</span> Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listas as $checklist)
                                <tr>
                                    <td>
                                        <div class="job-name">{{ $checklist->job_name }}</div>
                                    </td>
                                    <td>
                                        <div class="job-date">
                                            <span class="material-icons">calendar_today</span>
                                            {{ \Carbon\Carbon::parse($checklist->job_date)->format('d/m/Y') }}

                                        </div>
                                    </td>
                                    <td>
                                        <div class="user-info">
                                            <span class="material-icons">account_circle</span>
                                            {{ $checklist->user->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($checklist->is_completed == 1)
                                            <span class="status status-ok">Concluído</span>
                                        @else
                                            <span class="status status-pending">Não Concluído</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="timestamp">
                                            {{ $checklist->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($checklist->pdf_path)
                                            <a href="{{ asset('storage/' . $checklist->pdf_path) }}" target="_blank"
                                                class="btn-pdf">
                                                <span class="material-icons">download</span>
                                                Baixar
                                            </a>
                                        @else
                                            <span class="no-pdf">Sem PDF</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('checklist.edit', $checklist->id) }}"
                                                class="btn-action btn-view" title="editar">
                                                <span class="material-icons">editar</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="pagination-container">
                    {{ $listas->links() }}
                </div>
            @else
                <div class="empty-state">
                    <span class="material-icons">inbox</span>
                    <h3>Nenhum checklist encontrado</h3>
                    <p>Comece criando seu primeiro checklist!</p>
                    <a href="{{ route('checklist.create') }}" class="btn-create">
                        <span class="material-icons">add</span>
                        Novo Checklist
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Toggle menu mobile
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.navbar-menu').classList.toggle('show');
        });
    </script>

@endsection
