const toggleButton = document.getElementById('nav-toggle');
const navLinks = document.getElementById('nav-links');

toggleButton.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});

function toggleAccountFields() {
    const accountType = document.getElementById('accountType').value;
    const pessoaFisicaFields = document.getElementById('pessoaFisicaFields');
    const pessoaJuridicaFields = document.getElementById('pessoaJuridicaFields');
    const passwordFields = document.getElementById('passwordFields');
    
    const isAccountTypeSelected = accountType !== "";
    
    pessoaFisicaFields.style.display = isAccountTypeSelected && accountType === 'Pessoa Fisica' ? 'block' : 'none';
    pessoaJuridicaFields.style.display = isAccountTypeSelected && accountType === 'Pessoa Juridica' ? 'block' : 'none';
    passwordFields.style.display = isAccountTypeSelected ? 'block' : 'none';
}


function validarIdade() {
    const dataNascimento = document.getElementById('dataNascimento').value;
    if (dataNascimento) {
        const dataNascimentoFormatada = new Date(dataNascimento);
        const hoje = new Date();
        const diferencaAnos = hoje.getFullYear() - dataNascimentoFormatada.getFullYear();
        const diferencaMeses = hoje.getMonth() - dataNascimentoFormatada.getMonth();
        const diferencaDias = hoje.getDate() - dataNascimentoFormatada.getDate();

        if (diferencaAnos < 18 || (diferencaAnos === 18 && diferencaMeses < 0) || (diferencaAnos === 18 && diferencaMeses === 0 && diferencaDias < 0)) {
            document.getElementById('mensagemErroIdade').innerText = "Você deve ter mais de 18 anos.";
            document.getElementById('dataNascimento').value = "";
        } else {
            document.getElementById('mensagemErroIdade').innerText = "";
        }
    }
}

function validarCPF() {
    let cpf = document.getElementById('cpf').value;
    cpf = cpf.replace(/[^\d]/g, ''); // Remove formatação do CPF

    // Verifica se o CPF tem 11 dígitos
    if (cpf.length !== 11) {
        document.getElementById('errorMessageCPF').innerText = 'CPF inválido';
        document.getElementById('cpf').value = "";
        return false;
    }

    // Verifica se todos os dígitos são iguais, o que não é permitido
    if (/^(\d)\1+$/.test(cpf)) {
        document.getElementById('errorMessageCPF').innerText = 'CPF inválido';
        document.getElementById('cpf').value = "";
        return false;
    }

    // Calcula o primeiro dígito verificador
    let sum = 0;
    for (let i = 0; i < 9; i++) {
        sum += parseInt(cpf.charAt(i)) * (10 - i);
    }
    let remainder = 11 - (sum % 11);
    let digit1 = (remainder === 10 || remainder === 11) ? 0 : remainder;

    // Verifica se o primeiro dígito verificador está correto
    if (digit1 !== parseInt(cpf.charAt(9))) {
        document.getElementById('errorMessageCPF').innerText = 'CPF inválido';
        document.getElementById('cpf').value = "";
        return false;
    }

    // Calcula o segundo dígito verificador
    sum = 0;
    for (let i = 0; i < 10; i++) {
        sum += parseInt(cpf.charAt(i)) * (11 - i);
    }
    remainder = 11 - (sum % 11);
    let digit2 = (remainder === 10 || remainder === 11) ? 0 : remainder;

    // Verifica se o segundo dígito verificador está correto
    if (digit2 !== parseInt(cpf.charAt(10))) {
        document.getElementById('errorMessageCPF').innerText = 'CPF inválido';
        document.getElementById('cpf').value = "";
        return false;
    }

    // Formatar o CPF no formato padrão XXX.XXX.XXX-XX
    cpf = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
    document.getElementById('cpf').value = cpf;
    document.getElementById('errorMessageCPF').innerText = '';
    return true;
}

function validarSenha() {
    const senha = document.getElementById('password').value;
    const confirmarSenha = document.getElementById('confirmPassword').value;

    // Verifica se as senhas correspondem
    if (senha !== confirmarSenha) {
        document.getElementById('confirmPasswordError').innerText = "As senhas não correspondem.";
        return false;
    } else {
        document.getElementById('confirmPasswordError').innerText = "";
    }

    // Verifica se a senha é forte o suficiente
    if (senha.length < 8) {
        document.getElementById('confirmPasswordError').innerText = "A senha deve ter pelo menos 8 caracteres.";
        return false;
    }

    // Verifica se a senha contém pelo menos uma letra maiúscula
    if (!/[A-Z]/.test(senha)) {
        document.getElementById('confirmPasswordError').innerText = "A senha deve conter pelo menos uma letra maiúscula.";
        return false;
    }

    // Senha válida
    return true;
}

function formatarTelefone(str) {
    return str.replace(/\D/g, '') // Remove todos os caracteres que não são dígitos
        .replace(/(?:(^\+\d{2})?)(?:([1-9]{2})|([0-9]{3})?)(\d{4,5})(\d{4})/,
            (fullMatch, country, ddd, dddWithZero, prefixTel, suffixTel) => {
                // Formata o número de telefone no formato desejado
                if (country) {
                    return `+${country} (${ddd}) ${prefixTel}-${suffixTel}`;
                } else if (dddWithZero) {
                    return `(${dddWithZero}) ${prefixTel}-${suffixTel}`;
                } else {
                    return `(${ddd}) ${prefixTel}-${suffixTel}`;
                }
            }
        );
}

function validarTelefone(telefoneId, errorMessageId) {
    let telefone = document.getElementById(telefoneId).value;

    // Formata o número de telefone
    let telefoneFormatado = formatarTelefone(telefone);

    // Atualiza o valor do campo de telefone com o número formatado
    document.getElementById(telefoneId).value = telefoneFormatado;

    // Verifica se o telefone está preenchido corretamente
    if (telefoneFormatado.length < 14) {
        // Se o telefone não for válido, exibe uma mensagem de erro
        document.getElementById(errorMessageId).innerText = 'Telefone inválido';
        return false;
    } else {
        // Se o telefone for válido, limpa a mensagem de erro (se houver) e retorna true
        document.getElementById(errorMessageId).innerText = '';
        return true;
    }
}

function validarEmail() {
    const email = document.getElementById('email').value;
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const errorMessage = document.getElementById('errorMessageEmail');
    
    if (!regex.test(email)) {
        errorMessage.innerText = 'Email inválido';
        errorMessage.style.display = 'block'; // Exibe a mensagem de erro
        document.getElementById('email').value = ""; // Limpa o campo de email
    } else {
        errorMessage.innerText = ''; // Limpa a mensagem de erro
        errorMessage.style.display = 'none'; // Oculta a mensagem de erro
    }
}


function validarCNPJ() {
    // Obtém o valor do CNPJ do campo de entrada
    let cnpj = document.getElementById('cnpj').value;

    // Remove os caracteres especiais do CNPJ (pontos, traços e barra)
    cnpj = cnpj.replace(/[^\d]/g, '');

    // Verifica se o CNPJ tem 14 caracteres após a remoção dos especiais
    if (cnpj.length !== 14) {
        // Se o CNPJ não tiver 14 caracteres, exiba uma mensagem de erro
        document.getElementById('errorMessageCNPJ').innerText = 'CNPJ deve conter 14 dígitos';
        return false;
    } else {
        // Se o CNPJ tiver a quantidade correta de caracteres, formate o CNPJ e limpe a mensagem de erro (se houver)
        document.getElementById('cnpj').value = formatarCNPJ(cnpj);
        document.getElementById('errorMessageCNPJ').innerText = '';
        return true;
    }
}

// Função para formatar o CNPJ no padrão XX.XXX.XXX/XXXX-XX
function formatarCNPJ(cnpj) {
    return cnpj.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');
}

function validarDataAbertura() {
    // Obtém a data de abertura da empresa do campo de entrada
    let dataAbertura = new Date(document.getElementById('openDate').value);

    // Obtém a data atual
    let dataAtual = new Date();

    // Compara as datas
    if (dataAbertura > dataAtual) {
        // Se a data de abertura for maior que a data atual, exiba uma mensagem de erro
        document.getElementById('errorMessageDataAbertura').innerText = 'A data de abertura não pode ser maior que a data atual';
        return false;
    } else {
        // Se a data de abertura for válida, limpe a mensagem de erro (se houver)
        document.getElementById('errorMessageDataAbertura').innerText = '';
        return true;
    }
}

// Função para exibir mensagens de erro de registro
function exibirMensagemErroRegistro(mensagem) {
    const errorMessageDiv = document.getElementById('errorMessageRegistro');
    errorMessageDiv.innerText = mensagem;
    errorMessageDiv.style.display = 'block';
}

// Função para verificar se há um parâmetro de erro de registro na URL e exibir a mensagem correspondente
function verificarErroRegistro() {
    const erroRegistro = new URLSearchParams(window.location.search).get('registro');
    if (erroRegistro === 'erro_email') {
        exibirMensagemErroRegistro('O email inserido já está em uso. Por favor, escolha outro email.');
    }
}

// Chama a função para verificar erro de registro quando a página é carregada
window.addEventListener('DOMContentLoaded', verificarErroRegistro);
