# Guia: como deixamos as telas iguais e o componente de formulário

Este texto explica, em linguagem bem simples, **o que foi feito** no projeto para as páginas de produtos ficarem com o **mesmo visual** e como o **componente `meu-input`** funciona. Pense em uma **moldura** (layout), **folhas de papel** (cada página) e **etiquetas prontas** (componentes) que você cola em qualquer lugar.

---

## 1. A ideia principal (como se fosse um álbum de figurinhas)

Imagine que **todas as telas** (lista, cadastro, edição) usam o **mesmo tipo de folha**:

- O **fundo** da página é cinza (como uma mesa).
- No **meio** vai uma **caixinha branca** com cantos arredondados e sombra — é onde fica o conteúdo importante.
- No **topo da caixinha** tem um **título grande** e, do outro lado, um **botão** (por exemplo “Cadastrar” ou “Voltar à lista”).

Assim, quem abre o site **reconhece na hora** que ainda está no “mesmo lugar”, só mudou o que está escrito dentro.

---

## 2. O que é o “layout” (`layouts/crud.blade.php`)

O layout é a **moldura fixa** de todas as páginas que fazem `@extends('layouts.crud')`.

Ele desenha:

- A página inteira com fundo cinza (`bg-gray-100` no modo claro).
- Um **retângulo central** que não fica nem largo demais nem estreito demais (`max-w-6xl`), para caber tabelas e formulários.
- O **título da aba do navegador** (`@yield('title')`).
- O **título opcional** em cima (`@yield('heading')`).
- O **miolo** da página (`@yield('content')`) — é aqui que cada tela coloca o que quiser.

Também carrega o **CSS** com:

```blade
@vite('resources/css/app.css')
```

Isso liga o **Tailwind** (classes como `bg-white`, `rounded-lg`, `shadow`), que são como **adesivos com nome**: cada classe muda uma coisa (cor, tamanho, espaço).

---

## 3. Como a listagem (`index`) virou o “modelo visual”

Na página **Lista de produtos** fizemos assim:

1. Um **subtítulo** (`<h3>`) para contextualizar.
2. Uma **div** (caixa) com classes parecidas com estas ideias:
   - fundo branco, cantos redondos, sombra, espaço interno (`p-4` / `p-6`);
   - largura máxima grande (`max-w-6xl`) para a tabela não ficar espremida.
3. **Dentro da caixa**, uma **faixa** com:
   - à esquerda: título **“Produtos”**;
   - à direita: link azul **“Cadastrar”**.
4. A **tabela** dentro de um bloco com **rolagem horizontal** (`overflow-x-auto`) e tabela com largura mínima (`min-w-[640px]`), para em telas pequenas a caixa **não estourar** — aparece barra de rolagem embaixo em vez de sumir coluna.

Esse conjunto virou o **padrão**: mesma “caixinha branca”, mesmo tipo de cabeçalho, mesmo cuidado com responsividade.

---

## 4. O que fizemos no cadastro e na edição (`create` e `edit`)

Para ficar **igual à lista**, essas páginas:

1. **Não** trazem mais um HTML inteiro (`<!DOCTYPE>`, `<html>`, `<body>`) sozinhos — isso quebrava o layout, porque o layout já é o “esqueleto” da página.
2. Começam com:

   ```blade
   @extends('layouts.crud')
   ```

3. Definem título e cabeçalho:

   ```blade
   @section('title', 'Novo produto')
   @section('heading', 'Novo produto')
   ```

4. Dentro de `@section('content')` repetem a **mesma estrutura** do index:
   - um `<h3>` explicando (“Cadastro de produto” ou “Edição de produto”);
   - a **mesma caixa branca** (`max-w-6xl`, `rounded-lg`, `shadow`, etc.);
   - **mensagens** de sucesso ou erro com caixinhas coloridas (verde / vermelho);
   - **faixa de título** + botão **“Voltar à lista”** (cinza, como contraste do azul “Cadastrar”);
   - o **formulário** dentro, com largura confortável (`max-w-xl`).

Na **edição**, o controller passa também a lista de **tipos** (`types`), igual no cadastro, para o **select** mostrar nomes em vez de pedir um número solto.

---

## 5. O componente `meu-input` (a “etiqueta pronta”)

Antes, cada campo era um `<label>` + `<input>` repetido à mão. O componente é um **pacote** que já traz:

- o **texto do rótulo** (`label`);
- o **nome do campo** (`name`);
- as **classes** iguais para borda, fundo escuro no modo escuro, espaço embaixo (`mb-4`).

### 5.1. Campo de texto ou número (padrão)

```blade
<x-meu-input name="name" label="Nome:" type="text" value="{{ old('name') }}" required />
```

É como dizer: “monta um campo chamado `name`, mostra o rótulo ‘Nome:’, e copia o resto (`required`, `value`) para dentro do `<input>`”.

### 5.2. Área de texto (descrição)

Aqui o valor fica **entre as tags** do componente (porque textarea tem texto no meio):

```blade
<x-meu-input name="description" label="Descrição:" as="textarea" rows="3" cols="40">
    {{ old('description') }}
</x-meu-input>
```

A prop **`as="textarea"`** avisa: “não use `<input>`, use `<textarea>`”.

### 5.3. Lista suspensa (tipo do produto)

As **opções** vão no **conteúdo** do componente (slot):

```blade
<x-meu-input name="type_id" label="Tipo:" as="select" required>
    <option value="">Selecione…</option>
    @foreach ($types as $type)
        <option value="{{ $type->id }}" @selected(old('type_id') == $type->id)>
            {{ $type->name }}
        </option>
    @endforeach
</x-meu-input>
```

A prop **`as="select"`** monta o `<select>` e coloca essas `<option>` dentro.

### 5.4. Como “adaptar” o componente para outro projeto

Pense no arquivo `resources/views/components/meu-input.blade.php` como uma **receita**:

- Se quiser **outra cor de borda**, mude a string `controlClass` (onde estão as classes Tailwind).
- Se quiser um quarto tipo de campo (por exemplo **checkbox**), pode acrescentar um `@elseif ($as === 'checkbox')` e desenhar o HTML certo.
- Tudo que você passar a mais (`required`, `min`, `step`, `rows`…) vai para o controle certo porque usamos `{{ $attributes->merge(['class' => ...]) }}` (o Laravel junta suas opções com a aparência padrão).

**Resumo para lembrar:**  
`as` diz **qual formato** de campo é; `name` e `label` dizem **como chamar** e **o que mostrar**; o resto são **detalhes** (número mínimo, passo do preço, etc.).

---

## 6. O botão `meu-button` (opcional)

Existe também `meu-button.blade.php`, que monta um **botão de enviar** com cores (azul, vermelho, verde…) e estado **desabilitado**.  
Nas telas atuais usamos muitas vezes um `<button>` simples com classes Tailwind **iguais** ao estilo do link “Cadastrar”, para manter o **mesmo idioma visual**. Você pode trocar por `<x-meu-button>` se quiser padronizar ainda mais.

---

## 7. Checklist: repetir o mesmo visual em outra tela

1. Criar a view com `@extends('layouts.crud')`.
2. Preencher `@section('title')` e `@section('heading')`.
3. Dentro de `@section('content')`, copiar a **estrutura**:
   - `<h3>` opcional;
   - `div` da caixa branca com as mesmas classes base;
   - blocos de **mensagem** (sucesso / erro), se precisar;
   - **faixa** com `h1` + link de ação;
   - conteúdo (tabela ou `<form class="max-w-xl">`).
4. Usar **`<x-meu-input>`** nos campos para não repetir label + classe.
5. Garantir que o **controller** mande todas as variáveis que a view usa (por exemplo `types` no cadastro e na edição).

---

## 8. Ferramentas que entram nessa história (sem complicar)

| Ferramenta | O que faz (bem resumido) |
|------------|---------------------------|
| **Blade** | Mistura HTML com `@if`, `@foreach`, `@section` — é a linguagem dos arquivos `.blade.php`. |
| **Tailwind** | Classes no HTML que definem cor, espaço, borda — como **atalhos de estilo**. |
| **Vite** | Junta o CSS/JS modernos para o navegador; no layout entra com `@vite(...)`. |
| **Laravel** | Liga rotas, controllers e views; o `old()` lembra o que o usuário digitou se der erro de validação. |

---

## 9. Última dica (bem direta)

Se uma página **não** parecer com as outras, confira:

1. Ela usa **`@extends('layouts.crud')`**?
2. O conteúdo está **dentro** do card branco com as mesmas classes?
3. O **Vite** está rodando (`npm run dev`) ou você rodou `npm run build` para o CSS aparecer?

Com isso, qualquer pessoa (até quem está começando) consegue **copiar o modelo**, **trocar os textos** e **ajustar o `meu-input`** sem perder o visual unificado do projeto.
