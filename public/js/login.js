function validation() {
    //let username = document.getElementById("username").value;
    let pass = document.getElementById("password").value;
    //document.getElementById("submit").disabled = (username === "" || pass === "");
}

document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordField = document.getElementById('password');
    const isPassword = passwordField.type === 'password';

    // Alterna entre texto e senha
    passwordField.type = isPassword ? 'text' : 'password';

    // Troca o ícone usando os data-attributes definidos no HTML
    this.src = isPassword ? this.dataset.hide : this.dataset.show;
});











document.addEventListener('DOMContentLoaded', function() {
    class ErrorMessageHandler {
        constructor() {
            this.init();
        }

        init() {
            this.replaceExistingMessages();
            this.setupValidation();
        }

        // Substitui mensagens existentes (EMAIL e SENHA)
        replaceExistingMessages() {
            const existingMessages = document.querySelectorAll('.error-message');
            
            existingMessages.forEach(message => {
                if (message.textContent.trim()) {
                    let input = null;
                    
                    // Procura por form-group
                    const formGroup = message.closest('.form-group');
                    if (formGroup) {
                        input = formGroup.querySelector('input');
                    }
                    
                    // Procura por proximidade
                    if (!input) {
                        let currentElement = message.previousElementSibling;
                        while (currentElement && !input) {
                            if (currentElement.tagName === 'INPUT') {
                                input = currentElement;
                            } else {
                                input = currentElement.querySelector('input');
                            }
                            currentElement = currentElement.previousElementSibling;
                        }
                    }
                    
                    // Procura no container pai
                    if (!input) {
                        input = message.parentNode.querySelector('input');
                    }
                    
                    if (input) {
                        const translatedMessage = this.translateMessage(message.textContent.trim());
                        this.showError(input, translatedMessage);
                        message.style.display = 'none';
                    }
                }
            });
        }

        // Traduções completas
        translateMessage(originalMessage) {
            const translations = {
                // Credenciais gerais
                'These credentials do not match our records.': 'Essas credenciais não conferem com nossos registros.',
                
                // Email
                'The email field is required.': 'O campo email é obrigatório.',
                'The email must be a valid email address.': 'O email deve ser um endereço válido.',
                'The selected email is invalid.': 'O email selecionado é inválido.',
                
                // Senha
                'The password field is required.': 'O campo senha é obrigatório.',
                'The password must be at least 8 characters.': 'A senha deve ter pelo menos 8 caracteres.',
                'The password is incorrect.': 'A senha está incorreta.',
                'Invalid password.': 'Senha inválida.',
                'Wrong password.': 'Senha incorreta.',
                'Password does not match.': 'A senha não confere.',
                'Incorrect password.': 'Senha incorreta.',
                'The given password is invalid.': 'A senha fornecida é inválida.',
                
                // Outros
                'Too many login attempts.': 'Muitas tentativas de login.'
            };

            return translations[originalMessage] || originalMessage;
        }

        // Exibe erro com animação
        showError(input, message) {
            this.clearError(input);
            input.classList.add('input-error');

            const errorElement = document.createElement('span');
            errorElement.className = 'js-error-message';
            errorElement.textContent = message;

            // Insere após o input ou container
            const insertAfter = input.closest('.input-container') || input;
            insertAfter.parentNode.insertBefore(errorElement, insertAfter.nextSibling);

            setTimeout(() => errorElement.classList.add('show'), 10);
        }

        // Remove erro
        clearError(input) {
            const container = input.closest('.form-group') || input.parentNode;
            const existingError = container.querySelector('.js-error-message');
            
            if (existingError) {
                existingError.classList.add('fade-out');
                setTimeout(() => existingError.remove(), 300);
            }

            input.classList.remove('input-error');
        }

        // Adiciona erro programaticamente
        addError(fieldName, message) {
            const input = document.querySelector(`[name="${fieldName}"], input[type="${fieldName}"]`);
            if (input) {
                const translatedMessage = this.translateMessage(message);
                this.showError(input, translatedMessage);
            }
        }

        // Configuração de validação
        setupValidation() {
            const inputs = document.querySelectorAll('input');
            
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    if (input.classList.contains('input-error')) {
                        this.clearError(input);
                    }
                });
            });
        }
    }

    // Inicializa o sistema
    window.errorHandler = new ErrorMessageHandler();
});