@extends('layouts.master')

@section('title', 'Editar Checklist - Sistema')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/checklist.css') }}">
    <link rel="icon" href="{{ asset('images/promova.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .navbar {
            background-color: #fff;
            border-bottom: 2px solid #e9ecef;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
            padding: 12px 20px;
            position: sticky;
            top: 0;
            z-index: 999;
            width: 100%
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 10px
        }

        .navbar-logo {
            height: 45px;
            width: auto;
            border-radius: 8px
        }

        .navbar-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 18px
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
            transition: all .3s ease
        }

        .nav-link:hover {
            background-color: #f1f3f5;
            color: #007bff
        }

        .nav-link.active {
            background-color: #007bff;
            color: #fff
        }

        .nav-link .material-icons {
            font-size: 20px;
            vertical-align: middle
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 28px;
            color: #333
        }

        .edit-badge {
            background: #f59e0b;
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 10px
        }

        @media(max-width:768px) {
            .navbar-container {
                flex-wrap: wrap
            }

            .menu-toggle {
                display: block
            }

            .navbar-menu {
                display: none;
                width: 100%;
                flex-direction: column;
                gap: 10px;
                margin-top: 12px;
                background-color: #fff;
                border-top: 1px solid #e9ecef;
                padding-top: 10px
            }

            .navbar-menu.show {
                display: flex
            }

            .nav-link {
                justify-content: center;
                font-size: 1rem
            }

            .navbar-logo {
                height: 40px
            }

            .navbar-title {
                font-size: 1rem
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-8px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }
    </style>
@endsection

@section('content')
    <!-- Campo hidden com ID do checklist -->
    <input type="hidden" id="checklist_id" value="{{ $checklist->id }}">

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
                    <span class="material-icons">dashboard</span>Dashboard
                </a>
                <a href="{{ route('historico') }}" class="nav-link">
                    <span class="material-icons">history</span>Histórico
                </a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="nav-link">
                    <span class="material-icons">logout</span>Sair
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            </div>
        </div>
    </nav>

    <div class="container">
        <header class="header">
            <h1 class="title">
                <img class="icone_promova" src="{{ asset('images/promova.jpg') }}" alt="">
                Editar Checklist ✏️
                <span class="edit-badge">Editando</span>
            </h1>

            <div class="wrap">
                <div class="row">
                    <div class="input-line">
                        <label for="job">nome do job:</label>
                        <input type="text" id="job" value="{{ $checklist->job_name }}" placeholder="">
                    </div>
                    <div class="date-input">
                        <label for="date">Data:</label>
                        <input type="text" id="date"
                            value="{{ \Carbon\Carbon::parse($checklist->job_date)->format('d/m/Y') }}"
                            placeholder="dd/mm/aaaa">
                    </div>
                </div>
            </div>
        </header>

        <main class="main">
            @php
                $categories = [
                    ['icon' => 'camera_enhance', 'title' => 'Câmera'],
                    ['icon' => 'camera', 'title' => 'Lentes'],
                    ['icon' => 'flare', 'title' => 'Iluminação'],
                    ['icon' => 'volume_up', 'title' => 'Som'],
                    ['icon' => 'line_style', 'title' => 'Estabilização'],
                    ['icon' => 'extension', 'title' => 'Extras'],
                ];
            @endphp

            @foreach ($categories as $cat)
                <section class="category">
                    <div class="category-header">
                        <span class="material-icons category-icon">{{ $cat['icon'] }}</span>
                        <h2 class="category-title">{{ $cat['title'] }}</h2>
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
                                {{-- Itens serão carregados via JavaScript --}}
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
            @endforeach
        </main>

        <footer class="footer">
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('historico') }}" class="reset-btn" style="text-decoration: none;">
                    <span class="material-icons">arrow_back</span>
                    Voltar
                </a>
                <button class="save-btn" onclick="updateChecklist()">
                    <span class="material-icons">save</span>
                    Atualizar Checklist
                </button>
            </div>
        </footer>
    </div>
@endsection

@section('scripts')
    <script>
        window.csrfToken = '{{ csrf_token() }}';
        window.checklistId = {{ $checklist->id }};
        window.existingData = @json($checklistData);

        document.addEventListener("DOMContentLoaded", () => {
            // Toggle menu mobile
            const toggle = document.querySelector(".menu-toggle");
            const menu = document.querySelector(".navbar-menu");
            toggle.addEventListener("click", () => menu.classList.toggle("show"));

            // Carregar dados existentes
            loadExistingChecklistData(window.existingData);
        });

        // Função para carregar dados existentes
        function loadExistingChecklistData(data) {
            if (!data || !data.categories) return;

            const categories = document.querySelectorAll('.category');

            categories.forEach((category) => {
                const categoryTitle = category.querySelector('.category-title').textContent.trim();
                const tbody = category.querySelector('.checklist-table tbody');
                const items = data.categories[categoryTitle] || [];

                // Limpar tbody
                tbody.innerHTML = '';

                // Carregar cada item
                items.forEach((item) => {
                    const newRow = document.createElement('tr');
                    newRow.className = item.isEditable ? 'new-item-row' : '';

                    newRow.innerHTML = `
                        <td>
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <span class="${item.isEditable ? 'editable-item-name' : 'default-item-name'}" 
                                      ${item.isEditable ? 'contenteditable="true"' : ''}>${escapeHtml(item.name)}</span>
                                <button class="delete-item-btn" onclick="${item.isEditable ? 'removeItem(this)' : 'removeDefaultItem(this)'}" title="Remover item">
                                    <span class="material-icons">close</span>
                                </button>
                            </div>
                        </td>
                        <td><input type="number" min="0" value="${item.quantity || ''}"></td>
                        <td><input type="checkbox" ${item.ida ? 'checked' : ''}></td>
                        <td><input type="checkbox" ${item.volta ? 'checked' : ''}></td>
                        <td><input type="text" placeholder="Observações" value="${escapeHtml(item.observations || '')}"></td>
                    `;

                    tbody.appendChild(newRow);
                    setupRowEventListeners(newRow);
                });
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text || '';
            return div.innerHTML;
        }

        function setupRowEventListeners(row) {
            row.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.addEventListener('change', function() {
                    saveDataAutomatically();
                    animateCheckbox(this);
                });
            });

            row.querySelectorAll('input[type="number"], input[type="text"]').forEach(input => {
                input.addEventListener('input', saveDataAutomatically);
            });

            const editableName = row.querySelector('.editable-item-name');
            if (editableName) {
                editableName.addEventListener('input', saveDataAutomatically);
            }
        }

        // Função para atualizar checklist
        function updateChecklist() {
            const jobName = document.getElementById('job').value.trim();
            const jobDate = document.getElementById('date').value.trim();
            const checklistId = window.checklistId;

            if (!jobName) {
                showNotification('Por favor, preencha o nome do job!', 'error');
                document.getElementById('job').focus();
                return;
            }

            if (!jobDate || jobDate.length !== 10) {
                showNotification('Por favor, preencha a data no formato dd/mm/aaaa!', 'error');
                document.getElementById('date').focus();
                return;
            }

            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const checkedBoxes = document.querySelectorAll('input[type="checkbox"]:checked');
            const isFullyCompleted = checkboxes.length > 0 && checkedBoxes.length === checkboxes.length;

            const checklistData = collectChecklistData();

            const updateBtn = document.querySelector('.save-btn');
            const originalText = updateBtn.innerHTML;
            updateBtn.disabled = true;
            updateBtn.innerHTML = '<span class="material-icons">hourglass_empty</span>Atualizando...';

            fetch(`/checklist/${checklistId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        job_name: jobName,
                        job_date: jobDate,
                        checklist_data: checklistData,
                        is_completed: isFullyCompleted
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        if (data.pdf_url) {
                            setTimeout(() => showPdfDownloadNotification(data.pdf_url), 1500);
                        }
                        updateBtn.innerHTML = '<span class="material-icons">check</span>Atualizado!';
                        updateBtn.style.background = 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)';
                        setTimeout(() => {
                            updateBtn.innerHTML = originalText;
                            updateBtn.style.background = '';
                            updateBtn.disabled = false;
                        }, 2000);
                    } else {
                        showNotification(data.message || 'Erro ao atualizar', 'error');
                        updateBtn.innerHTML = originalText;
                        updateBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showNotification('Erro ao atualizar checklist.', 'error');
                    updateBtn.innerHTML = originalText;
                    updateBtn.disabled = false;
                });
        }
    </script>
    <script src="{{ asset('js/checklist.js') }}"></script>
@endsection
