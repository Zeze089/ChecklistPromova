@extends('layouts.master')

@section('title', 'Histórico de Checklists - Sistema Promova')

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
            --info: #0ea5e9;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-500: #64748b;
            --gray-700: #334155;
            --gray-900: #0f172a;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--gray-50) 0%, #e0f2fe 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* ======= NAVBAR ======= */
        .navbar {
            background-color: #ffffff;
            border-bottom: 2px solid #e9ecef;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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

        /* ======= CONTAINER PRINCIPAL ======= */
        .container-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* ======= HEADER DA PÁGINA ======= */
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 2rem 2.5rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(30, 58, 138, 0.3);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .page-header-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-header h1 .material-icons {
            font-size: 2rem;
        }

        .page-header p {
            margin: 0.5rem 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .btn-new-checklist {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            color: var(--primary);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-new-checklist:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            color: var(--primary);
        }

        /* ======= BARRA DE PESQUISA ======= */
        .search-bar {
            background: white;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid var(--gray-100);
        }

        .search-bar .material-icons {
            color: var(--gray-500);
            font-size: 1.5rem;
        }

        .search-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 1rem;
            color: var(--gray-900);
            background: transparent;
        }

        .search-input::placeholder {
            color: var(--gray-500);
        }

        .search-filters {
            display: flex;
            gap: 0.75rem;
        }

        .filter-btn {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            background: var(--gray-50);
            color: var(--gray-700);
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn:hover {
            background: var(--primary-light);
            color: white;
            border-color: var(--primary-light);
        }

        .filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .filter-btn .material-icons {
            font-size: 1rem;
        }

        /* ======= CARDS DE ESTATÍSTICAS ======= */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-mini-card {
            background: white;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--gray-100);
        }

        .stat-mini-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-mini-icon .material-icons {
            font-size: 1.25rem;
            color: white;
        }

        .stat-mini-icon.blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .stat-mini-icon.green { background: linear-gradient(135deg, #22c55e, #16a34a); }
        .stat-mini-icon.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }

        .stat-mini-content h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
        }

        .stat-mini-content span {
            font-size: 0.8rem;
            color: var(--gray-500);
        }

        /* ======= LISTA DE CHECKLISTS ======= */
        .checklist-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .checklist-card {
            background: white;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.3s ease;
        }

        .checklist-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-light);
        }

        .checklist-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .checklist-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .checklist-icon.completed {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: var(--success);
        }

        .checklist-icon.in-progress {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: var(--warning);
        }

        .checklist-icon .material-icons {
            font-size: 1.5rem;
        }

        .checklist-info {
            flex: 1;
            min-width: 0;
        }

        .checklist-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checklist-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.85rem;
            color: var(--gray-500);
        }

        .checklist-meta-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .checklist-meta-item .material-icons {
            font-size: 1rem;
        }

        .checklist-status {
            flex-shrink: 0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-badge.completed {
            background: #dcfce7;
            color: #166534;
        }

        .status-badge.in-progress {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge .material-icons {
            font-size: 0.9rem;
        }

        .checklist-actions {
            display: flex;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .action-btn .material-icons {
            font-size: 1.25rem;
        }

        .action-btn.view {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .action-btn.view:hover {
            background: #1d4ed8;
            color: white;
        }

        .action-btn.edit {
            background: #fef3c7;
            color: #d97706;
        }

        .action-btn.edit:hover {
            background: #d97706;
            color: white;
        }

        .action-btn.pdf {
            background: #fee2e2;
            color: #dc2626;
        }

        .action-btn.pdf:hover {
            background: #dc2626;
            color: white;
        }

        .action-btn.delete {
            background: #fecaca;
            color: #991b1b;
        }

        .action-btn.delete:hover {
            background: #991b1b;
            color: white;
        }

        /* ======= EMPTY STATE ======= */
        .empty-state {
            background: white;
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .empty-state .material-icons {
            font-size: 5rem;
            color: var(--gray-300);
            margin-bottom: 1.5rem;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--gray-500);
            margin-bottom: 1.5rem;
        }

        .empty-state .btn-new-checklist {
            display: inline-flex;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
        }

        /* ======= PAGINAÇÃO ======= */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination li a,
        .pagination li span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 0.75rem;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            background: white;
            color: var(--gray-700);
            border: 1px solid var(--gray-200);
        }

        .pagination li.active span {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination li a:hover {
            background: var(--gray-100);
        }

        .pagination li.disabled span {
            color: var(--gray-300);
            cursor: not-allowed;
        }

        /* ======= RESPONSIVIDADE ======= */
        @media (max-width: 992px) {
            .page-header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-filters {
                justify-content: flex-start;
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

            .page-header {
                padding: 1.5rem;
            }

            .page-header h1 {
                font-size: 1.35rem;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .checklist-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .checklist-checkbox {
                position: absolute;
                top: 1rem;
                right: 1rem;
            }

            .checklist-card {
                position: relative;
                padding-right: 3rem;
            }

            .checklist-status {
                width: 100%;
            }

            .checklist-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .search-filters {
                flex-wrap: wrap;
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
        <!-- Header da Página -->
        <div class="page-header">
            <div class="page-header-content">
                <div>
                    <h1>
                        <span class="material-icons">history</span>
                        Histórico de Checklists
                    </h1>
                    <p>Visualize e gerencie todos os seus checklists</p>
                </div>
                <a href="{{ route('checklist') }}" class="btn-new-checklist">
                    <span class="material-icons">add_circle</span>
                    Novo Checklist
                </a>
            </div>
        </div>

        <!-- Cards de Estatísticas -->
        @php
            $totalChecklists = $historicos->total();
            $completedCount = $historicos->filter(fn($c) => $c->is_completed)->count();
            $inProgressCount = $historicos->filter(fn($c) => !$c->is_completed)->count();
        @endphp

        <div class="stats-row">
            <div class="stat-mini-card">
                <div class="stat-mini-icon blue">
                    <span class="material-icons">folder</span>
                </div>
                <div class="stat-mini-content">
                    <h4>{{ $totalChecklists }}</h4>
                    <span>Total</span>
                </div>
            </div>
            <div class="stat-mini-card">
                <div class="stat-mini-icon green">
                    <span class="material-icons">check_circle</span>
                </div>
                <div class="stat-mini-content">
                    <h4>{{ $completedCount }}</h4>
                    <span>Concluídos</span>
                </div>
            </div>
            <div class="stat-mini-card">
                <div class="stat-mini-icon orange">
                    <span class="material-icons">pending</span>
                </div>
                <div class="stat-mini-content">
                    <h4>{{ $inProgressCount }}</h4>
                    <span>Em Andamento</span>
                </div>
            </div>
        </div>

        <!-- Barra de Pesquisa -->
        <div class="search-bar">
            <span class="material-icons">search</span>
            <input type="text"
                   class="search-input"
                   id="search-input"
                   placeholder="Pesquisar por nome do job ou data..."
                   value="{{ Request::get('pesquisar') }}">
            <div class="search-filters">
                <button class="filter-btn active" data-filter="all">
                    <span class="material-icons">view_list</span>
                    Todos
                </button>
                <button class="filter-btn" data-filter="completed">
                    <span class="material-icons">check_circle</span>
                    Concluídos
                </button>
                <button class="filter-btn" data-filter="in-progress">
                    <span class="material-icons">pending</span>
                    Em Andamento
                </button>
            </div>
        </div>

        <!-- Lista de Checklists -->
        <div class="checklist-list" id="checklist-list">
            @forelse ($historicos as $checklist)
                <div class="checklist-card"
                     data-status="{{ $checklist->is_completed ? 'completed' : 'in-progress' }}"
                     data-name="{{ strtolower($checklist->job_name) }}"
                     data-date="{{ $checklist->job_date }}">

                    <input type="checkbox" class="checklist-checkbox" value="{{ $checklist->id }}">

                    <div class="checklist-icon {{ $checklist->is_completed ? 'completed' : 'in-progress' }}">
                        <span class="material-icons">
                            {{ $checklist->is_completed ? 'task_alt' : 'pending_actions' }}
                        </span>
                    </div>

                    <div class="checklist-info">
                        <div class="checklist-title">
                            {{ $checklist->job_name }}
                        </div>
                        <div class="checklist-meta">
                            <div class="checklist-meta-item">
                                <span class="material-icons">event</span>
                                {{ \Carbon\Carbon::parse($checklist->job_date)->format('d/m/Y') }}
                            </div>
                            <div class="checklist-meta-item">
                                <span class="material-icons">person</span>
                                {{ $checklist->user->name ?? 'N/A' }}
                            </div>
                            @if ($checklist->completed_at)
                                <div class="checklist-meta-item">
                                    <span class="material-icons">check</span>
                                    Concluído em {{ $checklist->completed_at->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="checklist-status">
                        <span class="status-badge {{ $checklist->is_completed ? 'completed' : 'in-progress' }}">
                            <span class="material-icons">
                                {{ $checklist->is_completed ? 'check_circle' : 'schedule' }}
                            </span>
                            {{ $checklist->is_completed ? 'Concluído' : 'Em Andamento' }}
                        </span>
                    </div>

                    <div class="checklist-actions">
                        <a href="{{ route('historico.show', $checklist->id) }}"
                           class="action-btn view"
                           title="Visualizar">
                            <span class="material-icons">visibility</span>
                        </a>

                        @if (!$checklist->is_completed)
                            <a href="{{ route('checklist.edit', $checklist->id) }}"
                               class="action-btn edit"
                               title="Editar">
                                <span class="material-icons">edit</span>
                            </a>
                        @endif

                        @if ($checklist->pdf_url)
                            <a href="{{ $checklist->pdf_url }}"
                               class="action-btn pdf"
                               target="_blank"
                               title="Download PDF">
                                <span class="material-icons">picture_as_pdf</span>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <span class="material-icons">inventory_2</span>
                    <h3>Nenhum checklist encontrado</h3>
                    <p>Você ainda não criou nenhum checklist. Comece agora!</p>
                    <a href="{{ route('checklist') }}" class="btn-new-checklist">
                        <span class="material-icons">add_circle</span>
                        Criar Primeiro Checklist
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Paginação -->
        @if ($historicos->hasPages())
            <div class="pagination-wrapper">
                {{ $historicos->links() }}
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

            // Pesquisa em tempo real
            const searchInput = document.getElementById('search-input');
            const checklistCards = document.querySelectorAll('.checklist-card');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                checklistCards.forEach(card => {
                    const name = card.dataset.name;
                    const date = card.dataset.date;

                    if (name.includes(searchTerm) || date.includes(searchTerm)) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

            // Filtros
            const filterBtns = document.querySelectorAll('.filter-btn');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remover active de todos
                    filterBtns.forEach(b => b.classList.remove('active'));
                    // Adicionar active ao clicado
                    this.classList.add('active');

                    const filter = this.dataset.filter;

                    checklistCards.forEach(card => {
                        const status = card.dataset.status;

                        if (filter === 'all') {
                            card.style.display = 'flex';
                        } else if (filter === status) {
                            card.style.display = 'flex';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            // Animação de entrada dos cards
            checklistCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50 + (index * 50));
            });

            // Selecionar todos os checkboxes
            const allCheckboxes = document.querySelectorAll('.checklist-checkbox');
            let selectAllState = false;

            // Double click no header para selecionar todos (funcionalidade oculta)
            document.querySelector('.page-header').addEventListener('dblclick', () => {
                selectAllState = !selectAllState;
                allCheckboxes.forEach(cb => cb.checked = selectAllState);
            });
        });
    </script>
@endsection
