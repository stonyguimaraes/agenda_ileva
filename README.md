# 🚀 API de Agenda iLeva

Bem-vindo à API `agenda_ileva`. Esta é uma API RESTful para gerenciar contatos e seus números de telefone, construída com PHP, POO e a arquitetura MC (Model-Controller).

Toda a comunicação com a API é feita através de **JSON**.

## Banco de Dados

A API utiliza um banco de dados MySQL chamado `agenda_ileva`. A estrutura é composta por duas tabelas:

```sql
Tabela de Contatos
CREATE TABLE contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

Tabela de Telefones
CREATE TABLE telefones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_contato INT NOT NULL,
    numero VARCHAR(20) NOT NULL,
    tipo VARCHAR(50) DEFAULT 'celular',  -- Ex: 'celular', 'casa', 'trabalho'
    FOREIGN KEY (id_contato) 
        REFERENCES contatos(id) 
        ON DELETE CASCADE -- << IMPORTANTE!
);
```

---

## 👤 Endpoints de Contatos

Recurso principal para gerenciar os dados básicos dos contatos.

### 1. Listar todos os Contatos

* **Método:** `GET`
* **Endpoint:** `[URL_BASE]contato`
* **Descrição:** Retorna uma lista com todos os contatos cadastrados.
* **Resposta de Sucesso (200 OK):**
    ```json
    [
      {
        "id": 1,
        "nome": "Bruno Silva",
        "email": "bruno@email.com"
      },
      {
        "id": 2,
        "nome": "Ana Souza",
        "email": "ana@email.com"
      }
    ]
    ```

### 2. Buscar um Contato Específico

* **Método:** `GET`
* **Endpoint:** `[URL_BASE]contato/{id}`
* **Descrição:** Retorna um contato específico pelo seu `id`, incluindo sua lista de telefones.
* **Exemplo:** `[URL_BASE]contato/1`
* **Resposta de Sucesso (200 OK):**
    ```json
    {
      "id": 1,
      "nome": "Bruno Silva",
      "email": "bruno@email.com",
      "data_criacao": "2025-11-15 16:30:00",
      "telefones": [
        {
          "id": 1,
          "numero": "11 99999-8888",
          "tipo": "celular"
        }
      ]
    }
    ```

### 3. Criar novo Contato

* **Método:** `POST`
* **Endpoint:** `[URL_BASE]contato`
* **Corpo da Requisição (Body):**
    ```json
    {
      "nome": "Carlos Pereira",
      "email": "carlos@email.com"
    }
    ```
* **Resposta de Sucesso (201 Created):**
    ```json
    {
      "id": 3,
      "message": "Contato criado com sucesso."
    }
    ```

### 4. Atualizar um Contato

* **Método:** `PUT`
* **Endpoint:** `[URL_BASE]contato/{id}`
* **Exemplo:** `[URL_BASE]contato/3`
* **Corpo da Requisição (Body):**
    ```json
    {
      "nome": "Carlos Alberto Pereira",
      "email": "carlos.pereira@email.com"
    }
    ```
* **Resposta de Sucesso (200 OK):**
    ```json
    {
      "message": "Contato atualizado com sucesso."
    }
    ```

### 5. Deletar um Contato

* **Método:** `DELETE`
* **Endpoint:** `[URL_BASE]contato/{id}`
* **Exemplo:** `[URL_BASE]contato/3`
* **Descrição:** Deleta o contato. Graças ao `ON DELETE CASCADE` no banco de dados, todos os telefones associados a ele são automaticamente removidos.
* **Resposta de Sucesso (200 OK):**
    ```json
    {
      "message": "Contato excluído com sucesso."
    }
    ```

---

## Endpoints de Telefones

Recurso para gerenciar os números de telefone associados a um contato.

### 1. Listar todos os Telefones (Geral)

* **Método:** `GET`
* **Endpoint:** `[URL_BASE]telefone`
* **Descrição:** Retorna uma lista de **todos** os telefones no banco de dados, indicando a qual contato pertencem.
* **Resposta de Sucesso (200 OK):**
    ```json
    [
      {
        "id": 1,
        "numero": "11 99999-8888",
        "tipo": "celular",
        "id_contato": 1,
        "nome_contato": "Bruno Silva"
      },
      {
        "id": 2,
        "numero": "11 55555-4444",
        "tipo": "trabalho",
        "id_contato": 1,
        "nome_contato": "Bruno Silva"
      }
    ]
    ```

### 2. Buscar um Telefone Específico

* **Método:** `GET`
* **Endpoint:** `[URL_BASE]telefone/{id}`
* **Exemplo:** `[URL_BASE]telefone/2`
* **Descrição:** Retorna um telefone específico pelo seu próprio `id`.
* **Resposta de Sucesso (200 OK):**
    ```json
    {
      "id": 2,
      "id_contato": 1,
      "numero": "11 55555-4444",
      "tipo": "trabalho"
    }
    ```

### 3. Adicionar um Telefone a um Contato

* **Método:** `POST`
* **Endpoint:** `[URL_BASE]telefone`
* **Corpo da Requisição (Body):**
    ```json
    {
      "id_contato": 1,
      "numero": "11 2222-3333",
      "tipo": "casa"
    }
    ```
* **Resposta de Sucesso (201 Created):**
    ```json
    {
      "id": 3,
      "message": "Telefone adicionado."
    }
    ```

### 4. Atualizar um Telefone

* **Método:** `PUT`
* **Endpoint:** `[URL_BASE]telefone/{id}`
* **Exemplo:** `[URL_BASE]telefone/3`
* **Corpo da Requisição (Body):**
    ```json
    {
      "numero": "11 2222-7777",
      "tipo": "fax"
    }
    ```
* **Resposta de Sucesso (200 OK):**
    ```json
    {
      "message": "Telefone atualizado."
    }
    ```

### 5. Deletar um Telefone

* **Método:** `DELETE`
* **Endpoint:** `[URL_BASE]telefone/{id}`
* **Exemplo:** `[URL_BASE]telefone/3`
* **Descrição:** Deleta um número de telefone específico sem afetar o contato.
* **Resposta de Sucesso (200 OK):**
    ```json
    {
      "message": "Telefone excluído."
    }
    ```
