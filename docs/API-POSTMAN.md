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

## 2. Configuração inicial do Postman (passo a passo)

### 2.1 Antes de abrir o Postman — terminal

Em um terminal (PowerShell ou CMD), na pasta do projeto:

```bash
cd c:\xampp\htdocs\lojavirtual
php artisan serve
```

Deixe essa janela aberta. O servidor fica em `http://127.0.0.1:8000`.

> Se preferir XAMPP, use `http://localhost/lojavirtual/public` como base (sem `artisan serve`).

---

### 2.2 Criar o Environment (variáveis globais)

O **Environment** guarda URL e token para não repetir em cada requisição.

1. No Postman, canto superior direito, clique no ícone de **olho** (Environments) ou em **Environments** na barra lateral esquerda.
2. Clique em **+** (Create Environment).
3. Nome: `Loja Virtual - Local`.
4. Na tabela **Variable**, adicione:

| Variable   | Initial Value                    | Current Value                    |
|------------|----------------------------------|----------------------------------|
| `base_url` | `http://127.0.0.1:8000`          | `http://127.0.0.1:8000`          |
| `token`    | *(deixe vazio)*                  | *(deixe vazio)*                  |
| ` ` | `1`                            | `1`                              |

5. Clique em **Save**.
6. No canto superior direito, no dropdown de environment, selecione **Loja Virtual - Local** (tem que estar selecionado para `{{base_url}}` e `{{token}}` funcionarem).

**XAMPP:** troque `base_url` para `http://localhost/lojavirtual/public` (sem barra no final).

---

### 2.3 Criar a Collection

1. Barra lateral esquerda → **Collections** → **+** → **Blank collection**.
2. Nome: `Loja Virtual API`.
3. (Opcional) Clique nos três pontinhos da collection → **Edit** → aba **Authorization**:
   - Type: **Bearer Token**
   - Token: `{{token}}`
   - Isso herda auth em todas as requisições **exceto** login e GET (você pode sobrescrever em cada request).

4. Dentro da collection, crie **5 requests** (Add request):
   - `01 - Login`
   - `02 - Listar produtos`
   - `03 - Criar produto`
   - `04 - Atualizar produto`
   - `05 - Excluir produto`

---

### 2.4 Visão geral das abas de cada requisição

Em **qualquer** request, a área central tem estas abas:

| Aba | Quando usar neste projeto |
|-----|---------------------------|
| **Params** | Quase nunca (não usamos query string na API de produtos) |
| **Authorization** | Login: No Auth. Criar/Atualizar/Excluir: Bearer `{{token}}` |
| **Headers** | `Accept` e `Content-Type: application/json` onde houver body |
| **Body** | Login, POST e PUT: JSON. GET e DELETE: sem body |
| **Scripts** | (Opcional) Login: script para salvar token automaticamente |
| **Settings** | Deixar padrão |

Botão azul **Send** envia a requisição. Abaixo aparecem **Body**, **Headers** e status (200, 401, etc.) da resposta.

---

## 3. Request 01 — Login (`POST /api/login`)

Abra a request **01 - Login** na collection.

### Barra superior (método e URL)

| Campo | O que selecionar / digitar |
|-------|----------------------------|
| Método (dropdown à esquerda) | **POST** |
| URL | `{{base_url}}/api/login` |

Exemplo expandido: `http://127.0.0.1:8000/api/login`

### Aba **Params**

- Deixe **vazia** (nenhuma query key/value).

### Aba **Authorization**

| Campo | Valor |
|-------|-------|
| Type | **No Auth** |

O login não usa token; é ele quem **gera** o token.

### Aba **Headers**

Clique em **Headers** e marque ou adicione:

| Key | Value | Marcado? |
|-----|-------|----------|
| `Accept` | `application/json` | Sim |
| `Content-Type` | `application/json` | Sim |

> Se usar só a aba Body → raw → JSON, o Postman costuma preencher `Content-Type` sozinho.

### Aba **Body**

1. Selecione **Body**.
2. Marque **raw**.
3. No dropdown à direita de "raw", escolha **JSON** (não Text).
4. Cole:

```json
{
  "email": "test@example.com",
  "password": "password"
}
```

### Aba **Scripts** (opcional — salva o token sozinho)

Aba **Scripts** → sub-aba **Post-response** (ou "Tests" em versões antigas):

```javascript
if (pm.response.code === 200) {
    const json = pm.response.json();
    pm.environment.set("token", json.token);
    if (json.user) {
        console.log("Logado como: " + json.user.email);
    }
}
```

Assim, após **Send** com sucesso, `{{token}}` no environment é preenchido automaticamente.

### Clicar **Send**

**Resposta esperada:**

- Status: **200 OK**
- Body (JSON) com `token` e `user`

Se não usou o script acima: copie manualmente o valor de `"token"` (sem aspas) → Environment → `token` → Current Value → colar → Save.

---

## 4. Request 02 — Listar produtos (`GET /api/products`)

### Barra superior

| Campo | Valor |
|-------|-------|
| Método | **GET** |
| URL | `{{base_url}}/api/products` |

### Aba **Params**

- Vazia.

### Aba **Authorization**

| Campo | Valor |
|-------|-------|
| Type | **No Auth** |

Listagem é pública (feita em aula).

### Aba **Headers**

| Key | Value |
|-----|-------|
| `Accept` | `application/json` |

### Aba **Body**

- Selecione **none** (GET não envia corpo).

### **Send**

- Status: **200 OK**
- Body: array `[...]` com produtos. Anote um `id` existente para usar no PUT/DELETE, ou use `{{product_id}}`.

---

## 5. Request 03 — Criar produto (`POST /api/products`)

### Barra superior

| Campo | Valor |
|-------|-------|
| Método | **POST** |
| URL | `{{base_url}}/api/products` |

### Aba **Params**

- Vazia.

### Aba **Authorization**

| Campo | Valor |
|-------|-------|
| Type | **Bearer Token** |
| Token | `{{token}}` |

> Se a collection já tem Bearer `{{token}}`, esta aba pode herdar automaticamente. Confira se não está "Inherit auth from parent" com token vazio.

### Aba **Headers**

| Key | Value |
|-----|-------|
| `Accept` | `application/json` |
| `Content-Type` | `application/json` |

### Aba **Body**

- **raw** → **JSON**:

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

**type_id após seed:** 1 = Eletrônicos, 2 = Roupas, 3 = Alimentos, 4 = Livros, 5 = Outros.

### Aba **Scripts** (opcional)

Post-response:

```javascript
if (pm.response.code === 201) {
    const p = pm.response.json();
    pm.environment.set("product_id", p.id);
}
```

### **Send**

- Status: **201 Created**
- Body: objeto do produto com `"id": ...` e `"type": { ... }`

**Teste de erro 401:** em Authorization escolha **No Auth** e envie de novo → deve retornar 401.

---

## 6. Request 04 — Atualizar produto (`PUT /api/products/{id}`)

### Barra superior

| Campo | Valor |
|-------|-------|
| Método | **PUT** |
| URL | `{{base_url}}/api/products/{{product_id}}` |

Exemplo: `http://127.0.0.1:8000/api/products/1`

### Aba **Params**

- Vazia (o `id` vai na URL, não em Params).

### Aba **Authorization**

| Campo | Valor |
|-------|-------|
| Type | **Bearer Token** |
| Token | `{{token}}` |

### Aba **Headers**

| Key | Value |
|-----|-------|
| `Accept` | `application/json` |
| `Content-Type` | `application/json` |

### Aba **Body**

- **raw** → **JSON**:

```json
{
  "name": "Mouse Gamer Pro",
  "description": "Versão atualizada",
  "quantity": 30,
  "price": 149.90,
  "type_id": 1
}
```

Todos os campos são obrigatórios na validação (mesmo no update).

### **Send**

- Status: **200 OK**
- Body: produto atualizado.

**404:** troque `product_id` para um id que não existe (ex. 99999).

---

## 7. Request 05 — Excluir produto (`DELETE /api/products/{id}`)

### Barra superior

| Campo | Valor |
|-------|-------|
| Método | **DELETE** |
| URL | `{{base_url}}/api/products/{{product_id}}` |

### Aba **Params**

- Vazia.

### Aba **Authorization**

| Campo | Valor |
|-------|-------|
| Type | **Bearer Token** |
| Token | `{{token}}` |

### Aba **Headers**

| Key | Value |
|-----|-------|
| `Accept` | `application/json` |

### Aba **Body**

- **none** (DELETE sem corpo).

### **Send**

- Status: **200 OK**
- Body:

```json
{
  "message": "Produto excluído com sucesso."
}
```

Depois rode de novo o **02 - Listar** para confirmar que o produto sumiu.

---

## 8. Resumo visual — o que preencher em cada request

| Request | Método | URL | Auth | Body |
|---------|--------|-----|------|------|
| Login | POST | `{{base_url}}/api/login` | No Auth | JSON email/senha |
| Listar | GET | `{{base_url}}/api/products` | No Auth | none |
| Criar | POST | `{{base_url}}/api/products` | Bearer `{{token}}` | JSON produto |
| Atualizar | PUT | `{{base_url}}/api/products/{{product_id}}` | Bearer `{{token}}` | JSON produto |
| Excluir | DELETE | `{{base_url}}/api/products/{{product_id}}` | Bearer `{{token}}` | none |

---

## 9. Ordem recomendada (clique Send nesta sequência)

1. **01 - Login** → garante `{{token}}` preenchido  
2. **02 - Listar** → vê produtos atuais  
3. **03 - Criar** → anota `id` (ou script grava `product_id`)  
4. **02 - Listar** → confere novo produto  
5. **04 - Atualizar** → muda nome/preço  
6. **02 - Listar** → confere alteração  
7. **05 - Excluir**  
8. **02 - Listar** → produto não deve mais aparecer  
9. **03 - Criar** com Auth = No Auth → deve dar **401**  
10. **03 - Criar** com body `{}` e Bearer válido → deve dar **422**

---

## 10. Problemas comuns no Postman

| Problema | Causa provável | Solução |
|----------|----------------|---------|
| `Could not get response` | `php artisan serve` parado | Rode o servidor de novo |
| 404 na URL | `base_url` errado ou falta `/api` | Use `{{base_url}}/api/...` |
| 401 em POST/PUT/DELETE | Token vazio ou expirado | Rode Login de novo |
| 419 ou HTML em vez de JSON | URL apontando para rota web | Confira prefixo `/api` |
| 422 | JSON inválido ou campos faltando | Veja aba Body da **resposta** com lista `errors` |
| Variável não substitui | Environment não selecionado | Escolha "Loja Virtual - Local" no canto superior direito |

---

## 12. Comandos curl (referência)

**Login:**

```bash
curl -X POST "http://127.0.0.1:8000/api/login" -H "Accept: application/json" -H "Content-Type: application/json" -d "{\"email\":\"test@example.com\",\"password\":\"password\"}"
```

**Listar:**

```bash
curl -X GET "http://127.0.0.1:8000/api/products" -H "Accept: application/json"
```

**Criar** (substitua `SEU_TOKEN`):

```bash
curl -X POST "http://127.0.0.1:8000/api/products" -H "Accept: application/json" -H "Content-Type: application/json" -H "Authorization: Bearer SEU_TOKEN" -d "{\"name\":\"Mouse Gamer\",\"description\":\"Mouse RGB\",\"quantity\":25,\"price\":129.90,\"type_id\":1}"
```

**Atualizar:**

```bash
curl -X PUT "http://127.0.0.1:8000/api/products/1" -H "Accept: application/json" -H "Content-Type: application/json" -H "Authorization: Bearer SEU_TOKEN" -d "{\"name\":\"Mouse Pro\",\"description\":\"Atualizado\",\"quantity\":30,\"price\":149.90,\"type_id\":1}"
```

**Excluir:**

```bash
curl -X DELETE "http://127.0.0.1:8000/api/products/1" -H "Accept: application/json" -H "Authorization: Bearer SEU_TOKEN"
```

---

## 13. Resumo dos endpoints

| Método   | URL                      | Auth (`auth:sanctum`) | Descrição        |
|----------|--------------------------|------------------------|------------------|
| `POST`   | `/api/login`             | Não                    | Obter token      |
| `GET`    | `/api/products`          | Não                    | Listar produtos  |
| `POST`   | `/api/products`          | Sim                    | Criar produto    |
| `PUT`    | `/api/products/{id}`     | Sim                    | Atualizar produto|
| `DELETE` | `/api/products/{id}`     | Sim                    | Excluir produto  |

---

## 14. Regras de validação (referência)

| Campo         | Regras                                      |
|---------------|---------------------------------------------|
| `name`        | obrigatório, string, máx. 255               |
| `description` | opcional, string, máx. 255                  |
| `quantity`    | obrigatório, inteiro, mínimo 0                |
| `price`       | obrigatório, numérico, mínimo 0               |
| `type_id`     | obrigatório, deve existir em `types`        |
| `image`       | opcional, string, máx. 255 (caminho/URL)    |

---

## 15. Testes automatizados (opcional)

Para rodar os testes Pest da API no terminal:

```bash
php artisan test --filter=ProductApi
```

Ou todos os testes:

```bash
php artisan test
```
