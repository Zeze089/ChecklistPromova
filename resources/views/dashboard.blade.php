@extends('layouts.master')

@section('title', 'Dashboard - Sistema Promova')

@section('styles')
    <link rel="icon" href="{{ asset('images/promova.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            /* background: linear-gradient(135deg, #ffffff 0%, #0014ff 510% 100%); */
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            /* background: linear-gradient(135deg, #ffffff 0%, #0014ff 510% 100%); */
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

        /* Dashboard Container */
        .dashboard-container {
            max-width: 1400px;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        .welcome-section {
            background: white;
            /* border-bottom: 5px solid #224499; */
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

        /* Cards Grid */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .dashboard-card {
            background: white;
             border-bottom: 5px solid #224499;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .card-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }

        .card-icon.purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .card-icon.blue {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .card-icon.green {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .card-description {
            color: #718096;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: white;
           
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #718096;
            font-size: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                gap: 1rem;
            }

            .navbar-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .welcome-title {
                font-size: 2rem;
            }

            .cards-grid {
                grid-template-columns: 1fr;
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

                <a href="{{ route('checklist') }}" class="nav-link">
                    <span class="material-icons">checklist</span>
                    Listas dos não concluidos
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

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1 class="welcome-title">Bem-vindo, {{ Auth::user()->name }}! 🎥</h1>
            <p class="welcome-subtitle">Gerencie seus checklists de produção de forma eficiente</p>
        </div>

        <!-- Main Cards -->
        <div class="cards-grid">
            <a href="{{ route('checklist.create') }}" class="dashboard-card">
                <div class="card-icon purple">
                    <span class="material-icons">checklist_rtl</span>
                </div>
                <h2 class="card-title">Novo Checklist</h2>
                <p class="card-description">Crie um novo checklist de produção com todos os equipamentos e itens necessários
                    para o seu job.</p>
            </a>

            <a href="{{ route('historico') }}" class="dashboard-card">
                <div class="card-icon blue">
                    <span class="material-icons">history</span>
                </div>
                <h2 class="card-title">Histórico</h2>
                <p class="card-description">Visualize todos os checklists salvos anteriormente e acompanhe o status dos
                    equipamentos.</p>
            </a>

            <div class="dashboard-card" style="cursor: default;">
                <div class="card-icon green">
                    <span class="material-icons">analytics</span>
                </div>
                <h2 class="card-title">Relatórios</h2>
                <p class="card-description">Análise detalhada de equipamentos utilizados e estatísticas de produção (em
                    breve).</p>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number">{{ $totalChecklists ?? 0 }}</div>
                <div class="stat-label">Checklists Criados</div>
            </div>

            <div class="stat-card">
                <div class="stat-number">{{ $checklistsThisMonth ?? 0 }}</div>
                <div class="stat-label">Este Mês</div>
            </div>

            <div class="stat-card">
                <div class="stat-number">{{ $activeUsers ?? 1 }}</div>
                <div class="stat-label">Usuários Ativos</div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.querySelector(".menu-toggle");
            const menu = document.querySelector(".navbar-menu");
            toggle.addEventListener("click", () => {
                menu.classList.toggle("show");
            });
        });
    </script>

@endsection
