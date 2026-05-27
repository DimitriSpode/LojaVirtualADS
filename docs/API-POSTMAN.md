# Testes da API de Produtos (Postman e curl)

Documentação para testar a API REST de produtos com **Laravel Sanctum** na Loja Virtual.

---

## 1. Pré-requisitos

Antes de testar, garanta que o ambiente está pronto:

```bash
cd c:\xampp\htdocs\lojavirtual
composer install
cp .env.example .env   # se ainda não existir
php artisan key:generate
php artisan migrate
php artisan db:seed
```

O seeder cria:

- Usuário: `test@example.com` / senha: `password`
- Tipos (categorias): Eletrônicos, Roupas, Alimentos, Livros, Outros

Para rodar com servidor embutido do Laravel:

```bash
php artisan serve
```

Base URL neste caso: `http://127.0.0.1:8000`

### URL base no XAMPP

Se usar Apache (XAMPP) sem `artisan serve`:

```
http://localhost/lojavirtual/public
```

Todas as rotas da API ficam com prefixo `/api`. Exemplo completo:

```
http://localhost/lojavirtual/public/api/products
```

---

## 2. Configurar o Postman

1. Abra o **Postman**.
2. Crie uma **Collection** chamada `Loja Virtual API`.
3. Na collection (ou em um Environment), crie variáveis:

| Variável    | Valor inicial (exemplo)              |
|-------------|--------------------------------------|
| `base_url`  | `http://127.0.0.1:8000`              |
| `token`     | *(vazio — preenchido após o login)* |

4. Nas requisições protegidas, use **Authorization** → Type: **Bearer Token** → Token: `{{token}}`.

---

## 3. Obter token (login)

Rotas de criação, atualização e exclusão exigem token. Primeiro faça login.

### Postman

| Campo   | Valor                          |
|---------|--------------------------------|
| Método  | `POST`                         |
| URL     | `{{base_url}}/api/login`       |
| Headers | `Accept: application/json`     |
|         | `Content-Type: application/json` |
| Body    | **raw** → **JSON**             |

**Body (JSON):**

```json
{
  "email": "test@example.com",
  "password": "password"
}
```

### Resposta esperada (200 OK)

```json
{
  "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
  "user": {
    "id": 1,
    "name": "Test User",
    "email": "test@example.com"
  }
}
```

Copie o valor de `token` e cole na variável `{{token}}` do Postman.

### curl

```bash
curl -X POST "http://127.0.0.1:8000/api/login" ^
  -H "Accept: application/json" ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"test@example.com\",\"password\":\"password\"}"
```

### Erros comuns

| Status | Situação                                      |
|--------|-----------------------------------------------|
| 401    | E-mail ou senha incorretos                    |
| 422    | Campos `email` ou `password` ausentes/inválidos |

---

## 4. Listar produtos (GET — público)

Endpoint desenvolvido em aula. **Não exige token.**

### Postman

| Campo   | Valor                          |
|---------|--------------------------------|
| Método  | `GET`                          |
| URL     | `{{base_url}}/api/products`    |
| Headers | `Accept: application/json`     |

### Resposta esperada (200 OK)

Array JSON com produtos e relação `type`:

```json
[
  {
    "id": 1,
    "name": "Notebook",
    "description": "Notebook 15 polegadas",
    "quantity": 10,
    "price": "2999.99",
    "type_id": 1,
    "image": null,
    "created_at": "...",
    "updated_at": "...",
    "type": {
      "id": 1,
      "name": "Eletrônicos",
      ...
    }
  }
]
```

### curl

```bash
curl -X GET "http://127.0.0.1:8000/api/products" ^
  -H "Accept: application/json"
```

---

## 5. Cadastrar produto (POST — autenticado)

| Campo   | Valor                          |
|---------|--------------------------------|
| Método  | `POST`                         |
| URL     | `{{base_url}}/api/products`    |
| Auth    | Bearer Token → `{{token}}`     |
| Headers | `Accept: application/json`     |
|         | `Content-Type: application/json` |
| Body    | **raw** → **JSON**             |

**Body (JSON) — exemplo:**

```json
{
  "name": "Mouse Gamer",
  "description": "Mouse RGB com 6 botões",
  "quantity": 25,
  "price": 129.90,
  "type_id": 1,
  "image": null
}
```

> `type_id` deve existir na tabela `types` (após seed: 1 = Eletrônicos, 2 = Roupas, etc.).

### Resposta esperada (201 Created)

Objeto do produto criado, com `type` carregado.

### curl

Substitua `SEU_TOKEN` pelo token obtido no login.

```bash
curl -X POST "http://127.0.0.1:8000/api/products" ^
  -H "Accept: application/json" ^
  -H "Content-Type: application/json" ^
  -H "Authorization: Bearer SEU_TOKEN" ^
  -d "{\"name\":\"Mouse Gamer\",\"description\":\"Mouse RGB\",\"quantity\":25,\"price\":129.90,\"type_id\":1}"
```

### Erros comuns

| Status | Situação                                                |
|--------|---------------------------------------------------------|
| 401    | Sem header `Authorization` ou token inválido/expirado   |
| 422    | Validação falhou (campos obrigatórios, `type_id` inválido) |

**Exemplo de erro 422:**

```json
{
  "message": "The name field is required. (and 2 more errors)",
  "errors": {
    "name": ["The name field is required."],
    ...
  }
}
```

---

## 6. Atualizar produto (PUT — autenticado)

Substitua `{id}` pelo ID do produto (ex.: `1`).

### Postman

| Campo   | Valor                                |
|---------|--------------------------------------|
| Método  | `PUT`                                |
| URL     | `{{base_url}}/api/products/{id}`     |
| Auth    | Bearer Token → `{{token}}`           |
| Headers | `Accept: application/json`           |
|         | `Content-Type: application/json`     |
| Body    | **raw** → **JSON**                   |

**Body (JSON) — exemplo:**

```json
{
  "name": "Mouse Gamer Pro",
  "description": "Versão atualizada",
  "quantity": 30,
  "price": 149.90,
  "type_id": 1
}
```

### Resposta esperada (200 OK)

Produto atualizado com `type`.

### curl

```bash
curl -X PUT "http://127.0.0.1:8000/api/products/1" ^
  -H "Accept: application/json" ^
  -H "Content-Type: application/json" ^
  -H "Authorization: Bearer SEU_TOKEN" ^
  -d "{\"name\":\"Mouse Gamer Pro\",\"description\":\"Versão atualizada\",\"quantity\":30,\"price\":149.90,\"type_id\":1}"
```

### Erros comuns

| Status | Situação                          |
|--------|-----------------------------------|
| 401    | Sem autenticação                  |
| 404    | Produto com esse `id` não existe  |
| 422    | Dados inválidos                   |

---

## 7. Excluir produto (DELETE — autenticado)

### Postman

| Campo   | Valor                                |
|---------|--------------------------------------|
| Método  | `DELETE`                             |
| URL     | `{{base_url}}/api/products/{id}`     |
| Auth    | Bearer Token → `{{token}}`           |
| Headers | `Accept: application/json`           |

Sem body.

### Resposta esperada (200 OK)

```json
{
  "message": "Produto excluído com sucesso."
}
```

### curl

```bash
curl -X DELETE "http://127.0.0.1:8000/api/products/1" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer SEU_TOKEN"
```

### Erros comuns

| Status | Situação                |
|--------|-------------------------|
| 401    | Sem autenticação        |
| 404    | Produto não encontrado  |

---

## 8. Fluxo de teste sugerido (checklist)

Execute nesta ordem no Postman:

1. [ ] `POST /api/login` → salvar `token` em `{{token}}`
2. [ ] `GET /api/products` → ver lista atual
3. [ ] `POST /api/products` com Bearer → criar produto (anotar `id` retornado)
4. [ ] `GET /api/products` → confirmar que o novo produto aparece
5. [ ] `PUT /api/products/{id}` → alterar nome/preço
6. [ ] `GET /api/products` → confirmar alteração
7. [ ] `DELETE /api/products/{id}` → excluir
8. [ ] `GET /api/products` → confirmar que sumiu
9. [ ] `POST /api/products` **sem** Bearer → deve retornar **401**
10. [ ] `POST /api/products` com Bearer mas body vazio → deve retornar **422**

---

## 9. Resumo dos endpoints

| Método   | URL                      | Auth (`auth:sanctum`) | Descrição        |
|----------|--------------------------|------------------------|------------------|
| `POST`   | `/api/login`             | Não                    | Obter token      |
| `GET`    | `/api/products`          | Não                    | Listar produtos  |
| `POST`   | `/api/products`          | Sim                    | Criar produto    |
| `PUT`    | `/api/products/{id}`     | Sim                    | Atualizar produto|
| `DELETE` | `/api/products/{id}`     | Sim                    | Excluir produto  |

---

## 10. Regras de validação (referência)

| Campo         | Regras                                      |
|---------------|---------------------------------------------|
| `name`        | obrigatório, string, máx. 255               |
| `description` | opcional, string, máx. 255                  |
| `quantity`    | obrigatório, inteiro, mínimo 0                |
| `price`       | obrigatório, numérico, mínimo 0               |
| `type_id`     | obrigatório, deve existir em `types`        |
| `image`       | opcional, string, máx. 255 (caminho/URL)    |

---

## 11. Testes automatizados (opcional)

Para rodar os testes Pest da API no terminal:

```bash
php artisan test --filter=ProductApi
```

Ou todos os testes:

```bash
php artisan test
```
