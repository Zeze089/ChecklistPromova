@extends('layouts.master')

@section('title', 'Checklist de Produção - Sistema')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/checklist.css') }}">
    <link rel="icon" href="{{ asset('images/promova.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
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



    <div class="container">
        <!-- Header com informações do usuário -->
        {{-- <div class="user-info">
            <span class="user-name">Bem-vindo, {{ Auth::user()->name }}!</span>

             <a href="{{ route('historico') }}"
                class="logout-btn">
                <span class="material-icons"></span>
               Historico
            </a>

            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="logout-btn">
                <span class="material-icons">logout</span>
                Sair
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div> --}}

        <header class="header">

            <h1 class="title">
                <img class="icone_promova" src="{{ asset('images/promova.jpg') }}" alt="" srcset="">
                Checklist de Produção ✔
            </h1>

            <div class="wrap">
                <div class="row">
                    <div class="input-line">
                        <label for="job">nome do job:</label>
                        <input type="text" id="job" placeholder="">
                    </div>

                    <div class="date-input">
                        <label for="job">data:</label>
                        <input type="text" id="date" placeholder="__/__/____">
                    </div>
                </div>
            </div>

        </header>



        <main class="main">
            <!-- Câmera -->
            <section class="category">
                <div class="category-header">
                    <span class="material-icons category-icon">camera_enhance</span>
                    <h2 class="category-title">Câmera</h2>
                </div>
                <div class="table-container">
                    <table class="checklist-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qnt</th>
                                <th>Ida</th>
                                <th>Volta</th>
                                <th>Obs</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr>
                                <td>Câmera Principal</td>
                                <td><input type="number" min="0" name="camera[camera_principal][quantity]"></td>
                                <td><input type="checkbox" name="camera[camera_principal][ida]"></td>
                                <td><input type="checkbox" name="camera[camera_principal][volta]"></td>
                                <td><input type="text" placeholder="Observações" name="camera[camera_principal][obs]">
                                </td>
                            </tr> --}}


                        </tbody>
                    </table>
                    <div class="add-item-container">
                        <button class="add-item-btn" onclick="addNewItem(this)">
                            <span class="material-icons">add</span>
                            Adicionar Item
                        </button>
                    </div>
                </div>
            </section>

            <!-- Lentes -->
            <section class="category">
                <div class="category-header">
                    <span class="material-icons category-icon">camera</span>
                    <h2 class="category-title">Lentes</h2>
                </div>
                <div class="table-container">
                    <table class="checklist-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qnt</th>
                                <th>Ida</th>
                                <th>Volta</th>
                                <th>Obs</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr>
                                <td>Lente 50mm</td>
                                <td><input type="number" min="0" name="lentes[lente_50mm][quantity]"></td>
                                <td><input type="checkbox" name="lentes[lente_50mm][ida]"></td>
                                <td><input type="checkbox" name="lentes[lente_50mm][volta]"></td>
                                <td><input type="text" placeholder="Observações" name="lentes[lente_50mm][obs]"></td>
                            </tr> --}}


                        </tbody>
                    </table>
                    <div class="add-item-container">
                        <button class="add-item-btn" onclick="addNewItem(this)">
                            <span class="material-icons">add</span>
                            Adicionar Item
                        </button>
                    </div>
                </div>
            </section>

            <!-- Iluminação -->
            <section class="category">
                <div class="category-header">
                    <span class="material-icons category-icon">flare</span>
                    <h2 class="category-title">Iluminação</h2>
                </div>
                <div class="table-container">
                    <table class="checklist-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qnt</th>
                                <th>Ida</th>
                                <th>Volta</th>
                                <th>Obs</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr>
                                <td>LED Panel</td>
                                <td><input type="number" min="0" name="iluminacao[led_panel][quantity]"></td>
                                <td><input type="checkbox" name="iluminacao[led_panel][ida]"></td>
                                <td><input type="checkbox" name="iluminacao[led_panel][volta]"></td>
                                <td><input type="text" placeholder="Observações" name="iluminacao[led_panel][obs]">
                                </td>
                            </tr> --}}


                        </tbody>
                    </table>
                    <div class="add-item-container">
                        <button class="add-item-btn" onclick="addNewItem(this)">
                            <span class="material-icons">add</span>
                            Adicionar Item
                        </button>
                    </div>
                </div>
            </section>

            <!-- Som -->
            <section class="category">
                <div class="category-header">
                    <span class="material-icons category-icon">volume_up</span>
                    <h2 class="category-title">Som</h2>
                </div>
                <div class="table-container">
                    <table class="checklist-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qnt</th>
                                <th>Ida</th>
                                <th>Volta</th>
                                <th>Obs</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr>
                                <td>Microfone Lapela</td>
                                <td><input type="number" min="0" name="som[microfone_lapela][quantity]"></td>
                                <td><input type="checkbox" name="som[microfone_lapela][ida]"></td>
                                <td><input type="checkbox" name="som[microfone_lapela][volta]"></td>
                                <td><input type="text" placeholder="Observações" name="som[microfone_lapela][obs]">
                                </td>
                            </tr> --}}


                        </tbody>
                    </table>
                    <div class="add-item-container">
                        <button class="add-item-btn" onclick="addNewItem(this)">
                            <span class="material-icons">add</span>
                            Adicionar Item
                        </button>
                    </div>
                </div>
            </section>

            <!-- Estabilizador -->
            <section class="category">
                <div class="category-header">
                    <span class="material-icons category-icon">line_style</span>
                    <h2 class="category-title">Estabilização</h2>
                </div>
                <div class="table-container">
                    <table class="checklist-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qnt</th>
                                <th>Ida</th>
                                <th>Volta</th>
                                <th>Obs</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr>
                                <td>Gimbal</td>
                                <td><input type="number" min="0" name="estabilizacao[gimbal][quantity]"></td>
                                <td><input type="checkbox" name="estabilizacao[gimbal][ida]"></td>
                                <td><input type="checkbox" name="estabilizacao[gimbal][volta]"></td>
                                <td><input type="text" placeholder="Observações" name="estabilizacao[gimbal][obs]">
                                </td>
                            </tr> --}}


                        </tbody>
                    </table>
                    <div class="add-item-container">
                        <button class="add-item-btn" onclick="addNewItem(this)">
                            <span class="material-icons">add</span>
                            Adicionar Item
                        </button>
                    </div>
                </div>
            </section>

            <!-- Extras -->
            <section class="category">
                <div class="category-header">
                    <span class="material-icons category-icon">extension</span>
                    <h2 class="category-title">Extras</h2>
                </div>
                <div class="table-container">
                    <table class="checklist-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qnt</th>
                                <th>Ida</th>
                                <th>Volta</th>
                                <th>Obs</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                    <div class="add-item-container">
                        <button class="add-item-btn" onclick="addNewItem(this)">
                            <span class="material-icons">add</span>
                            Adicionar Item
                        </button>
                    </div>
                </div>
            </section>
        </main>


        <footer class="footer">
            <div style="display: flex; gap: 1rem;">
                <button class="reset-btn" onclick="restoreDefaultItems()">
                    <span class="material-icons">restore</span>
                    Restaurar Padrão
                </button>

                <button class="save-btn" onclick="saveChecklist()">
                    <span class="material-icons">save</span>
                    Salvar Checklist
                </button>
            </div>
        </footer>



    </div>
@endsection

@section('scripts')

    <script>
        // Adicionar token CSRF para requisições AJAX
        window.csrfToken = '{{ csrf_token() }}';

        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.querySelector(".menu-toggle");
            const menu = document.querySelector(".navbar-menu");
            toggle.addEventListener("click", () => {
                menu.classList.toggle("show");
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            const inputDate = document.getElementById("date");

            const today = new Date();
            const day = String(today.getDate()).padStart(2, '0');
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const year = today.getFullYear();

            inputDate.value = `${day}/${month}/${year}`;
        });
    </script>



    <script src="{{ asset('js/checklist.js') }}"></script>
@endsection
