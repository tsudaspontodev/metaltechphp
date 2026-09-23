// ===== Referências =====
const tabelaPessoas   = document.querySelector('#tabelaPessoas tbody');
const formPessoa      = document.getElementById('formPessoa');
const modalPessoaEl   = document.getElementById('modalPessoa');
const modalPessoa     = new bootstrap.Modal(modalPessoaEl);
const modalPessoaTitulo = document.getElementById('modalPessoaTitulo');

const modalExcluirEl  = document.getElementById('modalExcluir');
const modalExcluir    = new bootstrap.Modal(modalExcluirEl);
const nomeExcluirSpan = document.getElementById('nomeExcluir');
const idExcluirInput  = document.getElementById('idExcluir');
const btnConfirmarExclusao = document.getElementById('btnConfirmarExclusao');

const areaAlertas = document.getElementById('areaAlertas');

document.addEventListener('DOMContentLoaded', carregarPessoas);

// ===== Alertas =====
function mostrarAlerta(mensagem, tipo = 'success') {
    const div = document.createElement('div');
    div.className = `alert alert-${tipo} alert-dismissible fade show`;
    div.role = 'alert';
    div.innerHTML = `
        ${mensagem}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    areaAlertas.appendChild(div);

    setTimeout(() => {
        div.classList.remove('show');
        setTimeout(() => div.remove(), 300);
    }, 4000);
}

// ===== Listar pessoas =====
async function carregarPessoas() {
    try {
        const resp = await fetch('api/listar.php');
        const json = await resp.json();

        tabelaPessoas.innerHTML = '';

        if (!json.sucesso) {
            mostrarAlerta(json.mensagem || 'Erro ao carregar registros.', 'danger');
            return;
        }

        if (json.dados.length === 0) {
            tabelaPessoas.innerHTML = `
                <tr><td colspan="6" class="text-center text-muted py-4">Nenhuma pessoa cadastrada.</td></tr>
            `;
            return;
        }

        json.dados.forEach(pessoa => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${pessoa.id}</td>
                <td>${escapeHtml(pessoa.nome)}</td>
                <td>${escapeHtml(pessoa.celular)}</td>
                <td>${escapeHtml(pessoa.email)}</td>
                <td>${formatarData(pessoa.data_nascimento)}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary acoes-btn me-1" title="Editar" onclick="abrirModalEdicao(${pessoa.id})">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger acoes-btn" title="Excluir" onclick="abrirModalExclusao(${pessoa.id}, '${escapeHtml(pessoa.nome).replace(/'/g, "\\'")}')">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            `;
            tabelaPessoas.appendChild(tr);
        });
    } catch (e) {
        mostrarAlerta('Erro de comunicação com o servidor.', 'danger');
    }
}

function escapeHtml(texto) {
    const div = document.createElement('div');
    div.textContent = texto ?? '';
    return div.innerHTML;
}

function formatarData(dataIso) {
    if (!dataIso) return '';
    const [ano, mes, dia] = dataIso.split('-');
    return `${dia}/${mes}/${ano}`;
}

// ===== Abrir modal para novo cadastro =====
function abrirModalCadastro() {
    formPessoa.reset();
    formPessoa.classList.remove('was-validated');
    document.getElementById('pessoaId').value = '';
    modalPessoaTitulo.textContent = 'Nova Pessoa';
}

// ===== Abrir modal para edição (busca dados do registro) =====
async function abrirModalEdicao(id) {
    try {
        const resp = await fetch(`api/listar.php?id=${id}`);
        const json = await resp.json();

        if (!json.sucesso) {
            mostrarAlerta(json.mensagem || 'Registro não encontrado.', 'danger');
            return;
        }

        const pessoa = json.dados;

        formPessoa.reset();
        formPessoa.classList.remove('was-validated');

        document.getElementById('pessoaId').value = pessoa.id;
        document.getElementById('nome').value = pessoa.nome;
        document.getElementById('celular').value = pessoa.celular;
        document.getElementById('email').value = pessoa.email;
        document.getElementById('data_nascimento').value = pessoa.data_nascimento;

        modalPessoaTitulo.textContent = 'Editar Pessoa';
        modalPessoa.show();
    } catch (e) {
        mostrarAlerta('Erro de comunicação com o servidor.', 'danger');
    }
}

// ===== Submeter formulário (cadastro ou edição) =====
formPessoa.addEventListener('submit', async function (e) {
    e.preventDefault();

    if (!formPessoa.checkValidity()) {
        e.stopPropagation();
        formPessoa.classList.add('was-validated');
        return;
    }

    const formData = new FormData(formPessoa);

    try {
        const resp = await fetch('api/salvar.php', {
            method: 'POST',
            body: formData
        });
        const json = await resp.json();

        if (json.sucesso) {
            modalPessoa.hide();
            mostrarAlerta(json.mensagem, 'success');
            carregarPessoas();
        } else {
            mostrarAlerta(json.mensagem || 'Erro ao salvar registro.', 'danger');
        }
    } catch (e) {
        mostrarAlerta('Erro de comunicação com o servidor.', 'danger');
    }
});

// ===== Modal de exclusão =====
function abrirModalExclusao(id, nome) {
    idExcluirInput.value = id;
    nomeExcluirSpan.textContent = nome;
    modalExcluir.show();
}

btnConfirmarExclusao.addEventListener('click', async function () {
    const id = idExcluirInput.value;
    const formData = new FormData();
    formData.append('id', id);

    try {
        const resp = await fetch('api/excluir.php', {
            method: 'POST',
            body: formData
        });
        const json = await resp.json();

        modalExcluir.hide();

        if (json.sucesso) {
            mostrarAlerta(json.mensagem, 'success');
            carregarPessoas();
        } else {
            mostrarAlerta(json.mensagem || 'Erro ao excluir registro.', 'danger');
        }
    } catch (e) {
        modalExcluir.hide();
        mostrarAlerta('Erro de comunicação com o servidor.', 'danger');
    }
});
