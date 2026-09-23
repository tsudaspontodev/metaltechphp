<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CRUD de Pessoas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
    body { background-color: #f5f6fa; }
    .card-header h5 { margin: 0; }
    #tabelaPessoas tbody tr td { vertical-align: middle; }
    .acoes-btn { width: 36px; }
</style>
</head>
<body>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-people-fill me-2"></i>Cadastro de Pessoas</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPessoa" onclick="abrirModalCadastro()">
            <i class="bi bi-plus-lg me-1"></i> Nova Pessoa
        </button>
    </div>

    <!-- Alertas -->
    <div id="areaAlertas"></div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5>Pessoas cadastradas</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelaPessoas">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Celular</th>
                            <th>E-mail</th>
                            <th>Nascimento</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Preenchido via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ===== Modal Cadastro/Edição ===== -->
<div class="modal fade" id="modalPessoa" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formPessoa" novalidate>
        <div class="modal-header">
          <h5 class="modal-title" id="modalPessoaTitulo">Nova Pessoa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="pessoaId" name="id">

            <div class="mb-3">
                <label for="nome" class="form-label">Nome completo</label>
                <input type="text" class="form-control" id="nome" name="nome" maxlength="200" required>
                <div class="invalid-feedback">Informe o nome.</div>
            </div>

            <div class="mb-3">
                <label for="celular" class="form-label">Celular</label>
                <input type="text" class="form-control" id="celular" name="celular" maxlength="30" placeholder="(44) 99999-9999" required>
                <div class="invalid-feedback">Informe o celular.</div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" maxlength="200" required>
                <div class="invalid-feedback">Informe um e-mail válido.</div>
            </div>

            <div class="mb-3">
                <label for="data_nascimento" class="form-label">Data de nascimento</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
                <div class="invalid-feedback">Informe a data de nascimento.</div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg me-1"></i> Salvar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ===== Modal Confirmação de Exclusão ===== -->
<div class="modal fade" id="modalExcluir" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Confirmar exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Tem certeza que deseja excluir <strong id="nomeExcluir"></strong>? Esta ação não pode ser desfeita.
        <input type="hidden" id="idExcluir">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="btnConfirmarExclusao">
            <i class="bi bi-trash me-1"></i> Excluir
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
