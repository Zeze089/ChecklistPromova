
// script.js
document.addEventListener('DOMContentLoaded', function () {
    initializeChecklist();
    loadSavedData();
});

function initializeChecklist() {
    // Adicionar event listeners para todos os checkboxes
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            saveDataAutomatically();
            animateCheckbox(this);
        });
    });

    // Adicionar event listeners para inputs de número e texto
    const inputs = document.querySelectorAll('input[type="number"], input[type="text"]');
    inputs.forEach(input => {
        input.addEventListener('input', function () {
            saveDataAutomatically();
        });
    });

    // Adicionar event listeners para nomes de itens editáveis
    const editableNames = document.querySelectorAll('.editable-item-name');
    editableNames.forEach(name => {
        name.addEventListener('input', function () {
            saveDataAutomatically();
        });
    });

    // Adicionar botões de exclusão aos itens padrão
    addDeleteButtonsToDefaultItems();

    // Adicionar animação de entrada para as categorias
    const categories = document.querySelectorAll('.category');
    categories.forEach((category, index) => {
        category.style.opacity = '0';
        category.style.transform = 'translateY(20px)';

        setTimeout(() => {
            category.style.transition = 'all 0.5s ease';
            category.style.opacity = '1';
            category.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

function addDeleteButtonsToDefaultItems() {
    // Adicionar botões de exclusão a todos os itens padrão
    const tables = document.querySelectorAll('.checklist-table tbody');

    tables.forEach(tbody => {
        const rows = tbody.querySelectorAll('tr');

        rows.forEach(row => {
            const firstCell = row.querySelector('td:first-child');
            if (firstCell && !firstCell.querySelector('.delete-item-btn')) {
                // Verificar se já não é um item editável
                if (!firstCell.querySelector('.editable-item-name')) {
                    const itemText = firstCell.textContent.trim();

                    // Criar container com texto e botão de exclusão
                    firstCell.innerHTML = `
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span class="default-item-name">${itemText}</span>
                            <button class="delete-item-btn" onclick="removeDefaultItem(this)" title="Remover item">
                                <span class="material-icons">close</span>
                            </button>
                        </div>
                    `;
                }
            }
        });
    });
}


function removeDefaultItem(button) {
    const row = button.closest('tr');
    const table = button.closest('.checklist-table');
    const tableIndex = Array.from(document.querySelectorAll('.checklist-table')).indexOf(table);
    const rowIndex = Array.from(table.querySelectorAll('tbody tr')).indexOf(row);
    const itemName = row.querySelector('.default-item-name').textContent.trim();



    // Confirmar remoção
    if (confirm(`Tem certeza que deseja remover "${itemName}"?`)) {
        // Salvar informação do item removido para persistência
        if (!window.checklistData) {
            window.checklistData = { removedDefaultItems: [] };
        }
        if (!window.checklistData.removedDefaultItems) {
            window.checklistData.removedDefaultItems = [];
        }



        // Adicionar à lista de itens removidos
        window.checklistData.removedDefaultItems.push({
            tableIndex: tableIndex,
            originalIndex: rowIndex,
            name: itemName
        });



        // Animar saída
        row.style.animation = 'slideOutToRight 0.3s ease-in forwards';

        setTimeout(() => {
            row.remove();
            saveDataAutomatically();
            showNotification('Item padrão removido com sucesso!', 'info');
        }, 300);


    }

}



// Função auxiliar para restaurar itens padrão removidos (opcional)
function restoreDefaultItems() {
    if (confirm('Tem certeza que deseja restaurar todos os itens padrão removidos?')) {
        // Limpar lista de itens removidos
        if (window.checklistData) {
            window.checklistData.removedDefaultItems = [];
        }

        // Recarregar a página para restaurar estado original
        location.reload();

        showNotification('Itens padrão restaurados! Recarregando página...', 'success');
    }
}

// Função para resetar completamente o checklist (opcional)
function resetChecklist() {
    if (confirm('Tem certeza que deseja resetar completamente o checklist? Isso removerá todos os dados salvos.')) {
        // Limpar dados salvos
        window.checklistData = null;

        // Recarregar página
        location.reload();

        showNotification('Checklist resetado com sucesso!', 'info');
    }
}

function addNewItem(button) {
    const tableContainer = button.closest('.table-container');
    const table = tableContainer.querySelector('.checklist-table tbody');

    // Criar nova linha
    const newRow = document.createElement('tr');
    newRow.className = 'new-item-row';

    // Criar células da nova linha
    newRow.innerHTML = `
        <td>
            <div style="display: flex; align-items: center;">
                <span class="editable-item-name" contenteditable="true" data-placeholder="Digite o nome do item..." data-is-new="true"></span>
                <button class="delete-item-btn" onclick="removeItem(this)" title="Remover item">
                    <span class="material-icons">close</span>
                </button>
            </div>
        </td>
        <td><input type="number" min="0"></td>
        <td><input type="checkbox"></td>
        <td><input type="checkbox"></td>
        <td><input type="text" placeholder="Observações"></td>
    `;

    // Adicionar a nova linha à tabela
    table.appendChild(newRow);

    // Focar no nome do item para edição
    const editableName = newRow.querySelector('.editable-item-name');
    editableName.focus();

    // Selecionar todo o texto
    if (window.getSelection && document.createRange) {
        const range = document.createRange();
        range.selectNodeContents(editableName);
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
    }

    // Adicionar event listeners aos novos elementos
    const newCheckboxes = newRow.querySelectorAll('input[type="checkbox"]');
    newCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            saveDataAutomatically();
            animateCheckbox(this);
        });
    });

    const newInputs = newRow.querySelectorAll('input[type="number"], input[type="text"]');
    newInputs.forEach(input => {
        input.addEventListener('input', function () {
            saveDataAutomatically();
        });
    });

    editableName.addEventListener('input', function () {
        saveDataAutomatically();
    });

    // Adicionar placeholder behavior
    editableName.addEventListener('focus', function () {
        this.classList.remove('placeholder-visible');
    });

    editableName.addEventListener('blur', function () {
        if (this.textContent.trim() === '') {
            this.classList.add('placeholder-visible');
        }
    });

    // Permitir Enter para confirmar edição
    editableName.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.blur();
        }
    });

    // Animar o botão de adicionar
    button.style.transform = 'scale(0.95)';
    setTimeout(() => {
        button.style.transform = 'scale(1)';
    }, 150);

    // Atualizar progresso
    saveDataAutomatically();

    // Mostrar feedback visual
    showNotification('Item adicionado !', 'success');
}



function removeItem(button) {
    const row = button.closest('tr');
    const itemName = row.querySelector('.editable-item-name').textContent.trim();

    // Confirmar remoção
    if (confirm(`Tem certeza que deseja remover "${itemName}"?`)) {
        // Animar saída
        row.style.animation = 'slideOutToRight 0.3s ease-in forwards';

        setTimeout(() => {
            row.remove();
            saveDataAutomatically();
            showNotification('Item removido !', 'info');
        }, 300);
    }
}

function showNotification(message, type = 'info') {

    // Remove notificação existente
    const existing = document.querySelector('.notification');
    if (existing) existing.remove();

    // Cores por tipo
    const colors = {
        success: '#22c55e',
        info: '#224499',
        error: '#ef4444'
    };

    // Criar elemento
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${colors[type] || colors.info};
        color: #fff;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10001;
        font-weight: 500;
        opacity: 0;
        transform: translateX(20px);
        transition: opacity .3s ease, transform .3s ease;
    `;

    document.body.appendChild(notification);

    // Anima entrada
    requestAnimationFrame(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    });

    // Remover depois de 3s
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(20px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}




function saveCompletedChecklistToDatabase(isCompleted) {

    const checklistData = collectChecklistData(); // sua função atual de coleta

    fetch('/checklist/save-completed', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            job_name: document.getElementById('job_name').value,
            job_date: document.getElementById('job_date').value,
            checklist_data: checklistData,
            is_completed: isCompleted  // 🔥 ENVIADO AO LARAVEL
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotification(
                    isCompleted
                        ? 'Checklist concluído e salvo com sucesso!'
                        : 'Checklist salvo como NÃO concluído.',
                    'success'
                );
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(err => {
            showNotification('Erro ao salvar checklist', 'error');
        });
}


// Mostrar notificação de download do PDF
function showPdfDownloadNotification(pdfUrl) {

     const existing = document.querySelector('.pdf-download-notification');
    if (existing) existing.remove();

    const notification = document.createElement('div');
    notification.className = 'pdf-download-notification';
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 12px;">
            <span class="material-icons" style="color: #ef4444; font-size: 22px;">picture_as_pdf</span>
            <div style="flex: 1;">
                <strong>PDF Gerado!</strong>
                <br><small>Clique para baixar o checklist</small>
            </div>
            <a href="${pdfUrl}" target="_blank" class="pdf-download-btn">
                Baixar PDF
            </a>
        </div>
    `;

    // Estilo + animação
    notification.style.cssText = `
        position: fixed;
        top: 70px;
        right: 20px;
        z-index: 10001;
        background: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-left: 4px solid #ef4444;
        max-width: 350px;
        cursor: pointer;
        opacity: 0;
        transform: translateX(20px);
        transition: opacity .3s ease, transform .3s ease;
    `;

    // Estilo do botão
    const styleBtn = document.createElement('style');
    styleBtn.textContent = `
        .pdf-download-btn {
            background: #ef4444;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
        }
        .pdf-download-btn:hover {
            opacity: .9;
        }
    `;
    document.head.appendChild(styleBtn);

    document.body.appendChild(notification);

    // Anima entrada
    requestAnimationFrame(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    });

    // Clique na notificação fecha (mas não interfere no botão)
    notification.addEventListener('click', (e) => {
        if (!e.target.classList.contains('pdf-download-btn')) {
            notification.remove();
        }
    });

    // Remover depois de 15s
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(20px)';
        setTimeout(() => notification.remove(), 300);
    }, 15000);
}



/////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


function animateCheckbox(checkbox) {
    if (checkbox.checked) {
        // Animação quando marcado
        checkbox.style.transform = 'scale(1.2)';
        setTimeout(() => {
            checkbox.style.transform = 'scale(1.1)';
        }, 150);

        // Efeito de partículas
        createCheckEffect(checkbox);
    }
}

function createCheckEffect(checkbox) {
    const rect = checkbox.getBoundingClientRect();
    const particles = [];

    for (let i = 0; i < 6; i++) {
        const particle = document.createElement('div');
        particle.style.position = 'fixed';
        particle.style.width = '4px';
        particle.style.height = '4px';
        particle.style.backgroundColor = '#22c55e';
        particle.style.borderRadius = '50%';
        particle.style.pointerEvents = 'none';
        particle.style.zIndex = '9999';
        particle.style.left = (rect.left + rect.width / 2) + 'px';
        particle.style.top = (rect.top + rect.height / 2) + 'px';

        document.body.appendChild(particle);
        particles.push(particle);

        const angle = (i * 60) * Math.PI / 180;
        const velocity = 50;
        const vx = Math.cos(angle) * velocity;
        const vy = Math.sin(angle) * velocity;

        let x = 0;
        let y = 0;
        let opacity = 1;

        const animate = () => {
            x += vx * 0.02;
            y += vy * 0.02;
            opacity -= 0.02;

            particle.style.transform = `translate(${x}px, ${y}px)`;
            particle.style.opacity = opacity;

            if (opacity > 0) {
                requestAnimationFrame(animate);
            } else {
                document.body.removeChild(particle);
            }
        };

        requestAnimationFrame(animate);
    }
}

function showCompletionMessage() {
    // Criar overlay de congratulações
    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(30, 58, 138, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        transition: opacity 0.5s ease;
    `;

    const message = document.createElement('div');
    message.style.cssText = `
        background: white;
        padding: 3rem 2rem;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        transform: scale(0.8);
        transition: transform 0.5s ease;
    `;

    message.innerHTML = `
        <div style="font-size: 4rem; margin-bottom: 1rem;">🎉</div>
        <h2 style="color: #1e3a8a; margin-bottom: 1rem; font-size: 1.5rem;">Parabéns!</h2>
        <p style="color: #64748b; margin-bottom: 2rem;">Você completou todo o checklist de produção!</p>
        <button onclick="closeCompletionMessage()" style="
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease;
        ">Fechar</button>
    `;

    overlay.appendChild(message);
    document.body.appendChild(overlay);

    // Animar entrada
    setTimeout(() => {
        overlay.style.opacity = '1';
        message.style.transform = 'scale(1)';
    }, 10);

    // Adicionar função global para fechar
    window.closeCompletionMessage = function () {
        overlay.style.opacity = '0';
        message.style.transform = 'scale(0.8)';
        setTimeout(() => {
            document.body.removeChild(overlay);
            delete window.closeCompletionMessage;
        }, 500);
    };
}

function saveDataAutomatically() {
    const data = {
        timestamp: new Date().toISOString(),
        items: {}
    };

    // Salvar dados de todas as tabelas
    const tables = document.querySelectorAll('.checklist-table');
    tables.forEach((table, tableIndex) => {
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach((row, rowIndex) => {
            // Verificar se é item editável ou fixo
            const editableNameElement = row.querySelector('.editable-item-name');
            const itemName = editableNameElement
                ? editableNameElement.textContent.trim()
                : row.querySelector('td:first-child').textContent.trim();

            const quantityInput = row.querySelector('input[type="number"]');
            const checkboxes = row.querySelectorAll('input[type="checkbox"]');
            const obsInput = row.querySelector('input[type="text"]');

            const key = `${tableIndex}-${rowIndex}`;
            data.items[key] = {
                name: itemName,
                quantity: quantityInput ? quantityInput.value : '',
                ida: checkboxes[0] ? checkboxes[0].checked : false,
                volta: checkboxes[1] ? checkboxes[1].checked : false,
                observations: obsInput ? obsInput.value : '',
                isEditable: !!editableNameElement
            };
        });
    });

    // Salvar em variável (simulando localStorage)
    window.checklistData = data;
}

function loadSavedData() {
    // Carregar dados salvos (simulando localStorage)
    const data = window.checklistData;

    if (!data) return;

    const tables = document.querySelectorAll('.checklist-table');
    tables.forEach((table, tableIndex) => {
        const tbody = table.querySelector('tbody');
        const existingRows = tbody.querySelectorAll('tr');

        // Primeiro, carregar dados dos itens existentes
        existingRows.forEach((row, rowIndex) => {
            const key = `${tableIndex}-${rowIndex}`;
            const itemData = data.items[key];

            if (itemData && !itemData.isEditable) {
                const quantityInput = row.querySelector('input[type="number"]');
                const checkboxes = row.querySelectorAll('input[type="checkbox"]');
                const obsInput = row.querySelector('input[type="text"]');

                if (quantityInput) quantityInput.value = itemData.quantity || '';
                if (checkboxes[0]) checkboxes[0].checked = itemData.ida || false;
                if (checkboxes[1]) checkboxes[1].checked = itemData.volta || false;
                if (obsInput) obsInput.value = itemData.observations || '';
            }
        });

        // Depois, recriar itens editáveis (dinâmicos)
        Object.keys(data.items).forEach(key => {
            const itemData = data.items[key];
            const [savedTableIndex, savedRowIndex] = key.split('-').map(Number);

            if (savedTableIndex === tableIndex && itemData.isEditable) {
                // Verificar se o item já existe
                const existingEditableRow = Array.from(tbody.querySelectorAll('tr')).find(row => {
                    const editableName = row.querySelector('.editable-item-name');
                    return editableName && editableName.textContent.trim() === itemData.name;
                });

                if (!existingEditableRow) {
                    // Criar nova linha para item editável
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>
                            <div style="display: flex; align-items: center;">
                                <span class="editable-item-name" contenteditable="true">${itemData.name}</span>
                                <button class="delete-item-btn" onclick="removeItem(this)" title="Remover item">
                                    <span class="material-icons">close</span>
                                </button>
                            </div>
                        </td>
                        <td><input type="number" min="0" value="${itemData.quantity || ''}"></td>
                        <td><input type="checkbox" ${itemData.ida ? 'checked' : ''}></td>
                        <td><input type="checkbox" ${itemData.volta ? 'checked' : ''}></td>
                        <td><input type="text" placeholder="Observações" value="${itemData.observations || ''}"></td>
                    `;

                    tbody.appendChild(newRow);

                    // Adicionar event listeners aos novos elementos
                    const editableName = newRow.querySelector('.editable-item-name');
                    const newCheckboxes = newRow.querySelectorAll('input[type="checkbox"]');
                    const newInputs = newRow.querySelectorAll('input[type="number"], input[type="text"]');

                    newCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', function () {
                            saveDataAutomatically();
                            animateCheckbox(this);
                        });
                    });

                    newInputs.forEach(input => {
                        input.addEventListener('input', function () {
                            saveDataAutomatically();
                        });
                    });

                    editableName.addEventListener('input', function () {
                        saveDataAutomatically();
                    });
                }
            }
        });
    });
}


function saveChecklist() {
    const jobName = document.getElementById('job').value.trim();
    const jobDate = document.getElementById('date').value.trim();

    // Validação nome
    if (!jobName) {
        showNotification('Por favor, preencha o nome do job!', 'error');
        document.getElementById('job').focus();
        return;
    }

    // Validação da data
    const dateRegex = /^\d{2}\/\d{2}\/\d{4}$/;
    if (!dateRegex.test(jobDate)) {
        showNotification('Por favor, preencha a data no formato dd/mm/aaaa!', 'error');
        document.getElementById('date').focus();
        return;
    }

    // Checklist concluído?
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const isFullyCompleted = [...checkboxes].every(ch => ch.checked);

    // Dados
    const checklistData = collectChecklistData();

    // UI do botão
    const saveBtn = document.querySelector('.save-btn');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="material-icons">hourglass_empty</span> Salvando...';

    // Envio
    fetch('/checklist/save-completed', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
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

                saveBtn.innerHTML = '<span class="material-icons">check</span> Salvo!';
                saveBtn.style.background = 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)';

                setTimeout(() => {
                    saveBtn.innerHTML = originalText;
                    saveBtn.style.background = '';
                    saveBtn.disabled = false;
                }, 2000);

            } else {
                showNotification(data.message || 'Erro ao salvar checklist', 'error');
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showNotification('Erro ao salvar checklist. Tente novamente.', 'error');
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
}


/**
 * Coletar todos os dados do checklist
 */
function collectChecklistData() {
    const data = {
        timestamp: new Date().toISOString(),
        categories: {}
    };

    // Percorrer todas as categorias
    const categories = document.querySelectorAll('.category');

    categories.forEach((category) => {
        const categoryTitle = category.querySelector('.category-title').textContent.trim();
        const table = category.querySelector('.checklist-table tbody');
        const rows = table.querySelectorAll('tr');

        const items = [];

        rows.forEach((row) => {
            // Pegar nome do item (editável ou fixo)
            const editableNameElement = row.querySelector('.editable-item-name');
            const defaultNameElement = row.querySelector('.default-item-name');
            const itemName = editableNameElement
                ? editableNameElement.textContent.trim()
                : (defaultNameElement ? defaultNameElement.textContent.trim() : '');

            // Pular linhas sem nome ou com placeholder
            if (!itemName || itemName === '') return;

            const quantityInput = row.querySelector('input[type="number"]');
            const checkboxes = row.querySelectorAll('input[type="checkbox"]');
            const obsInput = row.querySelector('input[type="text"]');

            items.push({
                name: itemName,
                quantity: quantityInput ? quantityInput.value : '0',
                ida: checkboxes[0] ? checkboxes[0].checked : false,
                volta: checkboxes[1] ? checkboxes[1].checked : false,
                observations: obsInput ? obsInput.value : '',
                isEditable: !!editableNameElement
            });
        });

        data.categories[categoryTitle] = items;
    });

    return data;
}

/**
 * Função para atualizar checklist existente
 * Use esta função na view edit.blade.php
 */
function updateChecklist() {
    // Validar campos obrigatórios
    const jobName = document.getElementById('job').value.trim();
    const jobDate = document.getElementById('date').value.trim();
    const checklistId = document.getElementById('checklist_id').value; // Campo hidden com o ID

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

    // Calcular progresso
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const checkedBoxes = document.querySelectorAll('input[type="checkbox"]:checked');
    const totalItems = checkboxes.length;
    const completedItems = checkedBoxes.length;
    const isFullyCompleted = totalItems > 0 && completedItems === totalItems;

    // Coletar dados do checklist
    const checklistData = collectChecklistData();

    // Mostrar feedback visual no botão
    const updateBtn = document.querySelector('.update-btn') || document.querySelector('.save-btn');
    const originalText = updateBtn.innerHTML;
    updateBtn.disabled = true;
    updateBtn.innerHTML = '<span class="material-icons">hourglass_empty</span>Atualizando...';

    // Enviar para o servidor (PUT/PATCH para update)
    fetch(`/checklist/${checklistId}`, {
        method: 'PUT', // ou 'PATCH'
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            job_name: jobName,
            job_date: jobDate,
            checklist_data: checklistData,
            is_completed: isFullyCompleted
        })
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');

                // Se tiver PDF, mostrar notificação de download
                if (data.pdf_url) {
                    setTimeout(() => {
                        showPdfDownloadNotification(data.pdf_url);
                    }, 1500);
                }

                // Atualizar botão com sucesso
                updateBtn.innerHTML = '<span class="material-icons">check</span>Atualizado!';
                updateBtn.style.background = 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)';

                setTimeout(() => {
                    updateBtn.innerHTML = originalText;
                    updateBtn.style.background = '';
                    updateBtn.disabled = false;
                }, 2000);

            } else {
                showNotification(data.message || 'Erro ao atualizar checklist', 'error');
                updateBtn.innerHTML = originalText;
                updateBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showNotification('Erro ao atualizar checklist. Tente novamente.', 'error');
            updateBtn.innerHTML = originalText;
            updateBtn.disabled = false;
        });
}

/**
 * Carregar dados existentes do checklist na edição
 * Chamar esta função no DOMContentLoaded da página de edição
 */
function loadExistingChecklistData(checklistData) {
    if (!checklistData || !checklistData.categories) return;

    const categories = document.querySelectorAll('.category');

    categories.forEach((category) => {
        const categoryTitle = category.querySelector('.category-title').textContent.trim();
        const tbody = category.querySelector('.checklist-table tbody');
        const items = checklistData.categories[categoryTitle] || [];

        // Limpar itens dinâmicos existentes (manter apenas os padrão)
        const dynamicRows = tbody.querySelectorAll('tr.new-item-row');
        dynamicRows.forEach(row => row.remove());

        // Carregar itens
        items.forEach((item, index) => {
            if (item.isEditable) {
                // Criar linha para item editável
                const newRow = document.createElement('tr');
                newRow.className = 'new-item-row';
                newRow.innerHTML = `
                    <td>
                        <div style="display: flex; align-items: center;">
                            <span class="editable-item-name finalized" contenteditable="true">${item.name}</span>
                            <button class="delete-item-btn" onclick="removeItem(this)" title="Remover item">
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
            } else {
                // Atualizar item padrão existente
                const rows = tbody.querySelectorAll('tr:not(.new-item-row)');
                const row = rows[index];
                if (row) {
                    const qtyInput = row.querySelector('input[type="number"]');
                    const checkboxes = row.querySelectorAll('input[type="checkbox"]');
                    const obsInput = row.querySelector('input[type="text"]');

                    if (qtyInput) qtyInput.value = item.quantity || '';
                    if (checkboxes[0]) checkboxes[0].checked = item.ida || false;
                    if (checkboxes[1]) checkboxes[1].checked = item.volta || false;
                    if (obsInput) obsInput.value = item.observations || '';
                }
            }
        });
    });
}

// Função auxiliar para escapar HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Função auxiliar para configurar event listeners em uma linha
function setupRowEventListeners(row) {
    const checkboxes = row.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            saveDataAutomatically();
            animateCheckbox(this);
        });
    });

    const inputs = row.querySelectorAll('input[type="number"], input[type="text"]');
    inputs.forEach(input => {
        input.addEventListener('input', saveDataAutomatically);
    });

    const editableName = row.querySelector('.editable-item-name');
    if (editableName) {
        editableName.addEventListener('input', saveDataAutomatically);
    }
}


// Função para exportar dados
function exportToJSON() {
    const data = window.checklistData;
    if (!data) {
        alert('Nenhum dado para exportar');
        return;
    }

    const dataStr = JSON.stringify(data, null, 2);
    const dataBlob = new Blob([dataStr], { type: 'application/json' });
    const url = URL.createObjectURL(dataBlob);

    const link = document.createElement('a');
    link.href = url;
    link.download = `checklist-producao-${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    URL.revokeObjectURL(url);
}

// Função para imprimir checklist
function printChecklist() {
    window.print();
}

// Adicionar atalhos de teclado
document.addEventListener('keydown', function (e) {
    // Ctrl + S para salvar
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        saveChecklist();
    }

    // Ctrl + P para imprimir
    if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        printChecklist();
    }

    // Ctrl + E para exportar
    if (e.ctrlKey && e.key === 'e') {
        e.preventDefault();
        exportToJSON();
    }
});

// Adicionar efeitos de hover suaves
document.addEventListener('mouseover', function (e) {
    if (e.target.classList.contains('category')) {
        e.target.style.transform = 'translateY(-2px)';
    }
});

document.addEventListener('mouseout', function (e) {
    if (e.target.classList.contains('category')) {
        e.target.style.transform = 'translateY(0)';
    }
});

// Salvar dados automaticamente a cada 30 segundos
setInterval(saveDataAutomatically, 30000);







// AJUSTA DATA INPUT E REMOVE TUDO Q NÃO FOR NUMERO

const dateInput = document.getElementById("date");

dateInput.addEventListener("input", function (e) {
    let value = e.target.value.replace(/\D/g, ""); // remove tudo que não for número
    if (value.length > 8) value = value.slice(0, 8); // limita a 8 dígitos (ddmmaaaa)

    let formatted = "";
    if (value.length > 0) formatted = value.substring(0, 2);
    if (value.length > 2) formatted += "/" + value.substring(2, 4);
    if (value.length > 4) formatted += "/" + value.substring(4, 8);

    e.target.value = formatted;
});








// ===== FUNÇÃO 1: REMOVER BORDA AMARELA AO CLICAR FORA =====

// Variável global para controlar cliques
let isEditingItem = false;

// Modificar função addNewItem para incluir finalizador
function addNewItemWithFinalization(button) {
    const tableContainer = button.closest('.table-container');
    const table = tableContainer.querySelector('.checklist-table tbody');

    // Criar nova linha com drag handle
    const newRow = document.createElement('tr');
    newRow.className = 'new-item-row';
    newRow.draggable = true;

    // Criar células da nova linha com handle de drag
    newRow.innerHTML = `
        <td>
            <span class="material-icons drag-handle">drag_indicator</span>
            <div class="item-content">
                <span class="editable-item-name" contenteditable="true" data-placeholder="Digite o nome do item..." data-is-new="true"></span>
                <button class="delete-item-btn" onclick="removeItem(this)" title="Remover item">
                    <span class="material-icons">close</span>
                </button>
            </div>
        </td>
        <td><input type="number" min="0"></td>
        <td><input type="checkbox"></td>
        <td><input type="checkbox"></td>
        <td><input type="text" placeholder="Observações"></td>
    `;

    // Adicionar a nova linha à tabela
    table.appendChild(newRow);

    // Configurar drag and drop para a nova linha
    setupDragAndDrop(newRow);

    // Focar no nome do item para edição
    const editableName = newRow.querySelector('.editable-item-name');
    editableName.focus();
    isEditingItem = true;

    // Selecionar todo o texto
    if (window.getSelection && document.createRange) {
        const range = document.createRange();
        range.selectNodeContents(editableName);
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
    }

    // Event listeners para o novo item
    setupEditableItemEvents(editableName, newRow);

    // Event listeners para inputs
    const newCheckboxes = newRow.querySelectorAll('input[type="checkbox"]');
    newCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            saveDataAutomatically();
            animateCheckbox(this);
        });
    });

    const newInputs = newRow.querySelectorAll('input[type="number"], input[type="text"]');
    newInputs.forEach(input => {
        input.addEventListener('input', function () {
            saveDataAutomatically();
        });
    });

    // Animar o botão de adicionar
    button.style.transform = 'scale(0.95)';
    setTimeout(() => {
        button.style.transform = 'scale(1)';
    }, 150);

    // Atualizar progresso
    //updateProgress();
    saveDataAutomatically();

    // Mostrar feedback visual
    showNotification('Item adicionado !', 'success');
}

// Configurar eventos para item editável
function setupEditableItemEvents(editableName, row) {
    let blurTimeout = null;

    // Ao focar
    editableName.addEventListener('focus', function () {
        isEditingItem = true;
        this.classList.remove('placeholder-visible');
        // Cancelar qualquer blur pendente
        if (blurTimeout) {
            clearTimeout(blurTimeout);
            blurTimeout = null;
        }
    });

    // Ao sair do foco (blur) - com pequeno delay para evitar remoção acidental
    editableName.addEventListener('blur', function () {
        const element = this;
        isEditingItem = false;

        // Pequeno delay para permitir que o usuário clique de volta no campo
        blurTimeout = setTimeout(() => {
            // Verificar se o elemento ainda existe e não está focado
            if (document.body.contains(element) && document.activeElement !== element) {
                finalizeItemEditing(element, row);
            }
        }, 200);
    });

    // Enter para confirmar
    editableName.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (blurTimeout) {
                clearTimeout(blurTimeout);
                blurTimeout = null;
            }
            finalizeItemEditing(this, row);
        }
        if (e.key === 'Escape') {
            if (blurTimeout) {
                clearTimeout(blurTimeout);
                blurTimeout = null;
            }
            this.blur();
        }
    });

    // Input para salvar dados e marcar que o usuário digitou
    editableName.addEventListener('input', function () {
        // Marcar que o usuário começou a digitar
        this.dataset.hasInput = 'true';
        saveDataAutomatically();
    });
}

// Finalizar edição do item
function finalizeItemEditing(editableElement, row) {
    const itemName = editableElement.textContent.trim();

    // Se o campo estiver vazio
    if (itemName === '') {
        // Verificar se é um item novo que nunca recebeu input
        const isNew = editableElement.dataset.isNew === 'true';
        const hasInput = editableElement.dataset.hasInput === 'true';

        // Se é novo E nunca digitou nada, apenas mostrar placeholder
        if (isNew && !hasInput) {
            editableElement.classList.add('placeholder-visible');
            // NÃO marcar como não-novo ainda, dar mais chances
            return;
        }

        // Se já digitou algo e apagou, ou tentou várias vezes
        if (row && !isNew) {
            // Remover a linha
            row.style.animation = 'slideOutToRight 0.3s ease-in forwards';
            setTimeout(() => {
                row.remove();
                saveDataAutomatically();
                showNotification('Item removido (nome não pode ser vazio)', 'info');
            }, 300);
        } else {
            // Mostrar placeholder e marcar como não-novo
            editableElement.classList.add('placeholder-visible');
            editableElement.dataset.isNew = 'false';
        }
        return;
    }

    // Tem nome válido - adicionar classe de finalizado
    editableElement.classList.add('finalized');
    editableElement.dataset.isNew = 'false';
    editableElement.dataset.hasInput = 'false';

    // Event listener para editar novamente ao clicar (adicionar apenas uma vez)
    if (!editableElement.dataset.clickListenerAdded) {
        editableElement.dataset.clickListenerAdded = 'true';
        editableElement.addEventListener('click', function () {
            if (this.classList.contains('finalized')) {
                this.classList.remove('finalized');
                this.focus();
                isEditingItem = true;
            }
        });
    }

    saveDataAutomatically();
}

// Clique fora para finalizar edição
document.addEventListener('click', function (e) {
    if (!isEditingItem) return;

    // Verificar se clicou dentro de um elemento editável ou seus filhos
    const clickedEditable = e.target.closest('.editable-item-name');
    const clickedInRow = e.target.closest('tr.new-item-row');

    // Se clicou no próprio campo editável ou dentro da linha, não fazer nada
    if (clickedEditable || clickedInRow) {
        return;
    }

    // Se clicou fora de um elemento editável
    const activeEditable = document.querySelector('.editable-item-name:focus');
    if (activeEditable) {
        activeEditable.blur();
    }
});

// ===== FUNÇÃO 2: DRAG & DROP PARA REORDENAÇÃO =====

let draggedElement = null;
let dropIndicator = null;

// Configurar drag and drop para uma linha
function setupDragAndDrop(row) {
    const dragHandle = row.querySelector('.drag-handle');

    // Drag start
    row.addEventListener('dragstart', function (e) {
        draggedElement = this;
        this.classList.add('dragging');

        // Dados para transferência
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.outerHTML);
    });

    // Drag end
    row.addEventListener('dragend', function (e) {
        this.classList.remove('dragging');
        draggedElement = null;
        removeDropIndicators();
    });

    // Drag over
    row.addEventListener('dragover', function (e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';

        if (this !== draggedElement) {
            const rect = this.getBoundingClientRect();
            const midpoint = rect.top + rect.height / 2;

            removeDropIndicators();

            if (e.clientY < midpoint) {
                insertDropIndicator(this, 'before');
            } else {
                insertDropIndicator(this, 'after');
            }
        }
    });

    // Drop
    row.addEventListener('drop', function (e) {
        e.preventDefault();

        if (this !== draggedElement) {
            const tbody = this.parentNode;
            const rect = this.getBoundingClientRect();
            const midpoint = rect.top + rect.height / 2;

            if (e.clientY < midpoint) {
                tbody.insertBefore(draggedElement, this);
            } else {
                tbody.insertBefore(draggedElement, this.nextSibling);
            }

            // Salvar nova ordem
            saveDataAutomatically();
            showNotification('Item reordenado !', 'info');
        }

        removeDropIndicators();
    });

    // Drag leave
    row.addEventListener('dragleave', function (e) {
        // Remove indicator se saiu completamente da linha
        if (!this.contains(e.relatedTarget)) {
            removeDropIndicators();
        }
    });
}

// Inserir indicador de drop
function insertDropIndicator(element, position) {
    dropIndicator = document.createElement('tr');
    dropIndicator.className = 'drop-indicator';
    dropIndicator.innerHTML = '<td colspan="5" style="height: 2px; padding: 0; background: var(--light-navy);"></td>';

    if (position === 'before') {
        element.parentNode.insertBefore(dropIndicator, element);
    } else {
        element.parentNode.insertBefore(dropIndicator, element.nextSibling);
    }
}

// Remover indicadores de drop
function removeDropIndicators() {
    const indicators = document.querySelectorAll('.drop-indicator');
    indicators.forEach(indicator => indicator.remove());
    dropIndicator = null;
}

// Configurar drag and drop para itens existentes
function setupExistingItemsDragDrop() {
    const existingRows = document.querySelectorAll('.checklist-table tbody tr');
    existingRows.forEach(row => {
        // Adicionar handle se não tiver
        const firstCell = row.querySelector('td:first-child');
        if (firstCell && !firstCell.querySelector('.drag-handle')) {
            const content = firstCell.innerHTML;
            firstCell.innerHTML = `
                <span class="material-icons drag-handle">drag_indicator</span>
                <div class="item-content">${content}</div>
            `;
        }

        // Configurar drag
        row.draggable = true;
        setupDragAndDrop(row);
    });
}

// Inicializar ao carregar página
document.addEventListener('DOMContentLoaded', function () {
    setupExistingItemsDragDrop();
});

// ===== SOBRESCREVER FUNÇÃO ORIGINAL addNewItem =====

// Substituir a função original pela nova
window.addNewItem = addNewItemWithFinalization;




