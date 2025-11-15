const API_URL = "https://api.stonyguimaraes.com.br/public/";

const listaContatos = document.getElementById("lista-contatos");
const formContato = document.getElementById("form-contato");
const contatoIdInput = document.getElementById("contato-id");
const contatoNomeInput = document.getElementById("contato-nome");
const contatoEmailInput = document.getElementById("contato-email");

const modalContatoElement = document.getElementById("modalContato");
let modalContato;
const modalContatoTitulo = document.getElementById("modal-contato-titulo");
const btnNovoContato = document.getElementById("btn-novo-contato");

const modalTelefonesElement = document.getElementById("modalTelefones");
let modalTelefones;
const modalContactName = document.getElementById("modalContactName");
const modalListaTelefones = document.getElementById("lista-telefones-modal");
const formTelefone = document.getElementById("form-telefone");
const modalIdContatoInput = document.getElementById("modal-id-contato");
const modalNumeroInput = document.getElementById("modal-numero");
const modalTipoInput = document.getElementById("modal-tipo");

async function loadContatos() {
  try {
    const response = await fetch(API_URL + "contato");
    if (!response.ok) throw new Error("Erro ao buscar contatos");
    const contatos = await response.json();

    listaContatos.innerHTML = "";
    if (contatos.length === 0) {
      listaContatos.innerHTML =
        '<li class="list-group-item">Nenhum contato cadastrado.</li>';
      return;
    }

    contatos.forEach((contato) => {
      let telefonesHtml = "";
      if (contato.telefones && contato.telefones.length > 0) {
        telefonesHtml = '<div class="mt-2">';
        contato.telefones.forEach((tel) => {
          telefonesHtml += `
                        <span class="badge bg-secondary me-1">
                            <i class="bi bi-telephone-fill"></i> 
                            ${tel.numero} 
                            <small>(${tel.tipo || "N/A"})</small>
                        </span>
                    `;
        });
        telefonesHtml += "</div>";
      } else {
        telefonesHtml =
          '<div class="mt-2"><small class="text-muted"><i>Sem telefones cadastrados</i></small></div>';
      }

      listaContatos.innerHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-start"
                    data-id="${contato.id}"
                    data-nome="${contato.nome}"
                    data-email="${contato.email || ""}">
                    
                    <div>
                        <strong>${contato.nome}</strong><br>
                        <small class="text-muted">${
                          contato.email || "Sem email"
                        }</small>
                        ${telefonesHtml}
                    </div>
                    
                    <div class="mt-1">
                        <button class="btn btn-info btn-sm btn-telefones" title="Telefones (Gerenciar)">
                            <i class="bi bi-telephone-fill"></i>
                        </button>
                        <button class="btn btn-primary btn-sm btn-editar" title="Editar">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-excluir" title="Excluir">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                </li>
            `;
    });
  } catch (error) {
    console.error(error);
    listaContatos.innerHTML = `<li class="list-group-item text-danger">Erro ao carregar contatos. (Verifique console)</li>`;
  }
}

async function handleSalvarContato(e) {
  e.preventDefault();
  const id = contatoIdInput.value;
  const nome = contatoNomeInput.value;
  const email = contatoEmailInput.value;
  const data = { nome, email };
  const method = id ? "PUT" : "POST";
  const url = id ? `${API_URL}contato/${id}` : `${API_URL}contato`;

  try {
    const response = await fetch(url, {
      method: method,
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    if (!response.ok) throw new Error("Erro ao salvar contato");

    await response.json();

    if (e.submitter) {
      e.submitter.blur();
    }

    modalContato.hide();
    await loadContatos();
  } catch (error) {
    console.error(error);
    alert("Falha ao salvar. Verifique o console.");
  }
}

async function handleExcluirContato(id) {
  if (!confirm("Tem certeza que deseja excluir este contato?")) {
    return;
  }
  try {
    const response = await fetch(`${API_URL}contato/${id}`, {
      method: "DELETE",
    });
    if (!response.ok) throw new Error("Erro ao excluir");
    await response.json();
    await loadContatos();
  } catch (error) {
    console.error(error);
    alert("Falha ao excluir.");
  }
}

function handleEditarContato(liElement) {
  contatoIdInput.value = liElement.dataset.id;
  contatoNomeInput.value = liElement.dataset.nome;
  contatoEmailInput.value = liElement.dataset.email;
  modalContatoTitulo.innerText = "Editando Contato";

  modalContato.show();
}

function limparFormulario() {
  formContato.reset();
  contatoIdInput.value = "";
}

function handleAbrirModalNovo() {
  limparFormulario();
  modalContatoTitulo.innerText = "Adicionar Contato";
  modalContato.show();
}

async function openPhonesModal(id_contato, nome_contato) {
  modalContactName.innerText = `Telefones de: ${nome_contato}`;
  modalIdContatoInput.value = id_contato;
  modalListaTelefones.innerHTML = "<li>Carregando...</li>";
  modalTelefones.show();

  try {
    const response = await fetch(`${API_URL}contato/${id_contato}`);
    if (!response.ok) throw new Error("Erro ao buscar telefones");
    const contato = await response.json();
    renderTelefones(contato.telefones);
  } catch (error) {
    console.error(error);
    modalListaTelefones.innerHTML =
      '<li class="list-group-item text-danger">Erro ao carregar.</li>';
  }
}

function renderTelefones(telefones) {
  modalListaTelefones.innerHTML = "";
  if (telefones.length === 0) {
    modalListaTelefones.innerHTML =
      '<li class="list-group-item">Nenhum telefone cadastrado.</li>';
    return;
  }
  telefones.forEach((tel) => {
    modalListaTelefones.innerHTML += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>
                    <strong>${tel.numero}</strong>
                    <span class="badge bg-secondary ms-2">${
                      tel.tipo || "N/A"
                    }</span>
                </span>
                <button class="btn btn-danger btn-sm btn-excluir-telefone" data-id-telefone="${
                  tel.id
                }" title="Excluir Telefone">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </li>
        `;
  });
}

async function handleSalvarTelefone(e) {
  e.preventDefault();
  const id_contato = modalIdContatoInput.value;
  const numero = modalNumeroInput.value;
  const tipo = modalTipoInput.value;

  try {
    const response = await fetch(`${API_URL}telefone`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id_contato, numero, tipo }),
    });
    if (!response.ok) throw new Error("Erro ao adicionar telefone");
    await response.json();
    formTelefone.reset();

    await openPhonesModal(
      id_contato,
      modalContactName.innerText.replace("Telefones de: ", "")
    );

    await loadContatos();
  } catch (error) {
    console.error(error);
    alert("Falha ao salvar telefone.");
  }
}

async function handleExcluirTelefone(id_telefone) {
  if (!confirm("Tem certeza que deseja excluir este número?")) {
    return;
  }
  try {
    const response = await fetch(`${API_URL}telefone/${id_telefone}`, {
      method: "DELETE",
    });
    if (!response.ok) throw new Error("Erro ao excluir telefone");
    await response.json();

    const id_contato = modalIdContatoInput.value;

    await openPhonesModal(
      id_contato,
      modalContactName.innerText.replace("Telefones de: ", "")
    );

    await loadContatos();
  } catch (error) {
    console.error(error);
    alert("Falha ao excluir telefone.");
  }
}

function handleListaContatosClick(e) {
  const target = e.target.closest("button");
  if (!target) return;
  const li = target.closest(".list-group-item");
  const id = li.dataset.id;
  const nome = li.dataset.nome;

  if (target.classList.contains("btn-editar")) {
    handleEditarContato(li);
  } else if (target.classList.contains("btn-excluir")) {
    handleExcluirContato(id);
  } else if (target.classList.contains("btn-telefones")) {
    openPhonesModal(id, nome);
  }
}

function handleListaTelefonesClick(e) {
  const target = e.target.closest("button");
  if (target && target.classList.contains("btn-excluir-telefone")) {
    const id_telefone = target.dataset.idTelefone;
    handleExcluirTelefone(id_telefone);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  modalTelefones = new bootstrap.Modal(modalTelefonesElement);
  modalContato = new bootstrap.Modal(modalContatoElement);

  loadContatos();

  formContato.addEventListener("submit", handleSalvarContato);
  btnNovoContato.addEventListener("click", handleAbrirModalNovo);
  listaContatos.addEventListener("click", handleListaContatosClick);
  formTelefone.addEventListener("submit", handleSalvarTelefone);
  modalListaTelefones.addEventListener("click", handleListaTelefonesClick);

  modalContatoElement.addEventListener("hidden.bs.modal", limparFormulario);
  modalTelefonesElement.addEventListener("hidden.bs.modal", () => {
    formTelefone.reset();
  });

  modalTelefonesElement.addEventListener("hide.bs.modal", () => {
    if (
      document.activeElement &&
      typeof document.activeElement.blur === "function"
    ) {
      document.activeElement.blur();
    }
  });
});
