<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda iLeva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

    <div class="container mt-4">
        <header class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="display-5">Agenda iLeva</h1>
                <p class="lead">Consumo de API de Contatos</p>
            </div>
            <button class="btn btn-primary" id="btn-novo-contato">
                <i class="bi bi-plus-lg"></i> Adicionar Contato
            </button>
        </header>
        <hr>

        <div class="row">
            <div class="col-md-12">
                <h2>Meus Contatos</h2>
                <ul class="list-group" id="lista-contatos">
                    <li class="list-group-item">Carregando...</li>
                </ul>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modalContato" tabindex="-1" aria-labelledby="modalContatoTitulo" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-contato-titulo">Adicionar Contato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <form id="form-contato">
                    <div class="modal-body">
                        <input type="hidden" id="contato-id">

                        <div class="mb-3">
                            <label for="contato-nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="contato-nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="contato-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="contato-email">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTelefones" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalContactName">Gerenciar Telefones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-telefone" class="row g-3 mb-4 border-bottom pb-3">
                        <input type="hidden" id="modal-id-contato">
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="modal-numero" placeholder="Número (ex: 11 98765-4321)" required>
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="modal-tipo" placeholder="Tipo (ex: celular, casa)">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success w-100"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    </form>

                    <h4>Telefones Cadastrados</h4>
                    <ul class="list-group" id="lista-telefones-modal">
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="app.js"></script>
</body>

</html>