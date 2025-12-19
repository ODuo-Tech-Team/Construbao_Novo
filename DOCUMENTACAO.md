# Documentacao Completa - Construbao CMS

## Indice

1. [Visao Geral](#1-visao-geral)
2. [Estrutura de Arquivos](#2-estrutura-de-arquivos)
3. [Configuracao](#3-configuracao)
4. [Banco de Dados](#4-banco-de-dados)
5. [Sistema de Autenticacao](#5-sistema-de-autenticacao)
6. [Painel Administrativo](#6-painel-administrativo)
7. [Funcoes Helper](#7-funcoes-helper)
8. [Upload de Arquivos](#8-upload-de-arquivos)
9. [Seguranca](#9-seguranca)
10. [Frontend](#10-frontend)

---

## 1. Visao Geral

O **Construbao** e um CMS desenvolvido em PHP para gerenciamento de:
- **Equipamentos** para locacao
- **Postes padrao** para venda
- **Blog** com SEO integrado
- **Depoimentos** de clientes

### Stack Tecnologico
- **Backend:** PHP 7.4+
- **Banco de Dados:** MySQL/MariaDB com PDO
- **Frontend:** HTML5, CSS3, JavaScript
- **Editor de Texto:** Quill.js
- **Servidor:** Apache com mod_rewrite

---

## 2. Estrutura de Arquivos

```
construbao/
├── admin/                    # Painel administrativo
│   ├── assets/
│   │   ├── css/admin.css
│   │   └── js/
│   │       ├── admin.js
│   │       └── seo-analyzer.js
│   ├── includes/
│   │   ├── header.php
│   │   └── footer.php
│   ├── blog/                 # CRUD de posts do blog
│   ├── categorias-blog/      # CRUD de categorias do blog
│   ├── categorias-equip/     # CRUD de categorias de equipamentos
│   ├── depoimentos/          # CRUD de depoimentos
│   ├── equipamentos/         # CRUD de equipamentos
│   ├── postes/               # CRUD de postes
│   ├── usuarios/             # CRUD de usuarios
│   ├── index.php             # Dashboard
│   ├── login.php
│   └── logout.php
├── assets/                   # Arquivos estaticos do frontend
│   ├── css/
│   ├── js/
│   └── images/
├── database/
│   └── schema.sql            # Schema do banco de dados
├── includes/                 # Core do sistema
│   ├── config.php            # Configuracoes
│   ├── database.php          # Camada de banco de dados
│   ├── auth.php              # Autenticacao
│   ├── functions.php         # Funcoes utilitarias
│   ├── header.php            # Header do frontend
│   ├── footer.php            # Footer do frontend
│   ├── cta-final.php         # Componente CTA
│   └── whatsapp-button.php   # Botao flutuante WhatsApp
├── uploads/                  # Arquivos enviados pelos usuarios
│   ├── equipamentos/
│   ├── postes/
│   ├── blog/
│   └── depoimentos/
├── .env                      # Variaveis de ambiente (NAO COMMITAR)
├── .env.example              # Exemplo de variaveis
├── .htaccess                 # Configuracoes Apache
└── index.php                 # Pagina inicial
```

---

## 3. Configuracao

### 3.1 Variaveis de Ambiente (.env)

```env
# Ambiente
APP_ENV=development
APP_DEBUG=true

# Banco de Dados
DB_HOST=localhost
DB_NAME=construbao
DB_USER=root
DB_PASS=sua_senha
DB_CHARSET=utf8mb4

# Site
SITE_URL=auto
SITE_NAME=Construbao
SITE_DESCRIPTION=Venda de Poste Padrao e Locacao de Equipamentos

# WhatsApp
WHATSAPP_NUMBER=5516997007775
WHATSAPP_MESSAGE=Ola! Vim pelo site e gostaria de um orcamento.

# Upload
MAX_UPLOAD_SIZE=2097152
```

### 3.2 Constantes Definidas (config.php)

| Constante | Descricao | Valor Padrao |
|-----------|-----------|--------------|
| `SITE_ROOT` | Diretorio raiz da aplicacao | Auto-detectado |
| `SITE_URL` | URL base do site | Auto-detectado |
| `SITE_NAME` | Nome do site | "Construbao" |
| `DB_HOST` | Host do banco de dados | "localhost" |
| `DB_NAME` | Nome do banco | "construbao" |
| `DB_USER` | Usuario do banco | "root" |
| `DB_PASS` | Senha do banco | "" |
| `UPLOAD_PATH` | Caminho fisico de uploads | `/uploads/` |
| `UPLOAD_URL` | URL de uploads | `{SITE_URL}/uploads/` |
| `ASSETS_URL` | URL de assets | `{SITE_URL}/assets/` |
| `MAX_UPLOAD_SIZE` | Tamanho max de upload | 2MB |
| `ALLOWED_IMAGE_TYPES` | Tipos de imagem permitidos | jpeg, png, webp, gif |
| `DEV_MODE` | Modo de desenvolvimento | true |

---

## 4. Banco de Dados

### 4.1 Diagrama de Relacionamentos

```
┌─────────────────────┐
│     usuarios        │
├─────────────────────┤
│ id (PK)             │
│ nome                │
│ email (UNIQUE)      │
│ senha (bcrypt)      │
│ nivel (admin/editor)│
│ ativo               │
│ ultimo_login        │
└─────────────────────┘
         │
         │ autor_id (FK)
         ▼
┌─────────────────────┐      ┌─────────────────────┐
│    blog_posts       │◄─────│  categorias_blog    │
├─────────────────────┤      ├─────────────────────┤
│ id (PK)             │      │ id (PK)             │
│ categoria_id (FK)   │      │ nome                │
│ autor_id (FK)       │      │ slug (UNIQUE)       │
│ titulo              │      │ descricao           │
│ slug (UNIQUE)       │      │ cor                 │
│ resumo              │      │ ativo               │
│ conteudo            │      └─────────────────────┘
│ imagem_destaque     │
│ focus_keyword       │
│ meta_title          │
│ meta_description    │
│ seo_score           │
│ status              │
│ publicado_em        │
│ views               │
└─────────────────────┘

┌─────────────────────┐      ┌─────────────────────────┐
│   equipamentos      │◄─────│ categorias_equipamentos │
├─────────────────────┤      ├─────────────────────────┤
│ id (PK)             │      │ id (PK)                 │
│ categoria_id (FK)   │      │ nome                    │
│ nome                │      │ slug (UNIQUE)           │
│ slug (UNIQUE)       │      │ descricao               │
│ descricao           │      │ icone                   │
│ caracteristicas     │      │ ordem                   │
│ imagem              │      │ ativo                   │
│ meta_title          │      └─────────────────────────┘
│ meta_description    │
│ ativo               │
│ destaque            │
│ ordem               │
│ views               │
└─────────────────────┘

┌─────────────────────┐      ┌─────────────────────┐
│      postes         │      │    depoimentos      │
├─────────────────────┤      ├─────────────────────┤
│ id (PK)             │      │ id (PK)             │
│ nome                │      │ nome                │
│ slug (UNIQUE)       │      │ foto                │
│ descricao           │      │ texto               │
│ caracteristicas     │      │ avaliacao (1-5)     │
│ imagem              │      │ data_depoimento     │
│ meta_title          │      │ ativo               │
│ meta_description    │      │ ordem               │
│ ativo               │      └─────────────────────┘
│ ordem               │
│ views               │
└─────────────────────┘

┌─────────────────────┐
│   configuracoes     │
├─────────────────────┤
│ chave (PK)          │
│ valor               │
│ descricao           │
└─────────────────────┘
```

### 4.2 Tabelas Detalhadas

#### usuarios
Armazena usuarios administrativos do sistema.

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,          -- Hash bcrypt
    nivel ENUM('admin', 'editor') DEFAULT 'editor',
    ativo TINYINT(1) DEFAULT 1,
    ultimo_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Niveis de Acesso:**
- `admin`: Acesso total, incluindo gerenciamento de usuarios
- `editor`: Acesso ao conteudo, sem gerenciar usuarios

#### equipamentos
Catalogo de equipamentos para locacao.

```sql
CREATE TABLE equipamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NULL,
    nome VARCHAR(150) NOT NULL,
    slug VARCHAR(150) UNIQUE NOT NULL,
    descricao TEXT NULL,
    caracteristicas JSON NULL,            -- Array de caracteristicas
    imagem VARCHAR(255) NULL,
    meta_title VARCHAR(70) NULL,
    meta_description VARCHAR(160) NULL,
    ativo TINYINT(1) DEFAULT 1,
    destaque TINYINT(1) DEFAULT 0,
    ordem INT DEFAULT 0,
    views INT DEFAULT 0,
    FOREIGN KEY (categoria_id) REFERENCES categorias_equipamentos(id) ON DELETE SET NULL
);
```

#### blog_posts
Posts do blog com campos SEO completos.

```sql
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NULL,
    autor_id INT NULL,
    titulo VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    resumo VARCHAR(300) NULL,
    conteudo LONGTEXT NULL,
    imagem_destaque VARCHAR(255) NULL,

    -- Campos SEO
    focus_keyword VARCHAR(100) NULL,
    meta_title VARCHAR(70) NULL,
    meta_description VARCHAR(160) NULL,
    canonical_url VARCHAR(255) NULL,
    og_title VARCHAR(100) NULL,
    og_description VARCHAR(200) NULL,
    og_image VARCHAR(255) NULL,
    seo_score INT DEFAULT 0,

    status ENUM('rascunho', 'publicado', 'agendado') DEFAULT 'rascunho',
    publicado_em DATETIME NULL,
    views INT DEFAULT 0
);
```

**Status do Post:**
- `rascunho`: Visivel apenas no admin
- `publicado`: Visivel publicamente
- `agendado`: Publicacao futura programada

---

## 5. Sistema de Autenticacao

### 5.1 Fluxo de Login

```
┌─────────────┐     ┌──────────────┐     ┌─────────────┐
│   Usuario   │────▶│  login.php   │────▶│  Dashboard  │
│  (email +   │     │              │     │  /admin/    │
│   senha)    │     │ - Valida     │     │             │
└─────────────┘     │   CSRF       │     └─────────────┘
                    │ - Verifica   │
                    │   email/senha│
                    │ - Inicia     │
                    │   sessao     │
                    └──────────────┘
```

### 5.2 Funcoes de Autenticacao

```php
// Verificar se usuario esta logado
isLoggedIn(): bool

// Obter dados do usuario logado
getLoggedUser(): ?array

// Verificar se e admin
isAdmin(): bool

// Realizar login
login(string $email, string $senha): bool

// Realizar logout
logout(): void

// Middleware - requer login
requireLogin(): void

// Middleware - requer admin
requireAdmin(): void

// Hash de senha
hashPassword(string $password): string

// Validar forca da senha
validatePassword(string $password): array
```

### 5.3 Variaveis de Sessao

```php
$_SESSION['user_id']    // ID do usuario
$_SESSION['user_nome']  // Nome do usuario
$_SESSION['user_nivel'] // Nivel de acesso (admin/editor)
$_SESSION['csrf_token'] // Token CSRF
$_SESSION['flash']      // Mensagens flash
```

### 5.4 Credenciais Padrao

| Campo | Valor |
|-------|-------|
| URL | `/admin/login.php` |
| Email | `admin@construbao.com.br` |
| Senha | `admin123` |

> **IMPORTANTE:** Altere a senha padrao apos o primeiro acesso!

---

## 6. Painel Administrativo

### 6.1 Dashboard (`/admin/`)

Exibe:
- Total de equipamentos ativos
- Total de postes ativos
- Total de posts do blog
- Total de usuarios ativos
- Ultimos 5 posts do blog
- Ultimos 5 equipamentos

### 6.2 Modulos CRUD

Cada modulo segue a estrutura:

```
/admin/{modulo}/
├── index.php    # Listagem com busca e paginacao
├── criar.php    # Formulario de criacao
├── editar.php   # Formulario de edicao
└── excluir.php  # Exclusao de registro
```

#### 6.2.1 Usuarios (`/admin/usuarios/`)

**Permissao:** Apenas admin

| Campo | Tipo | Obrigatorio | Validacao |
|-------|------|-------------|-----------|
| nome | text | Sim | - |
| email | email | Sim | Unico |
| senha | password | Sim (criar) | Min 6 caracteres |
| nivel | select | Sim | admin/editor |
| ativo | checkbox | Nao | - |

**Restricoes:**
- Nao pode excluir a si mesmo
- Nao pode desativar a si mesmo
- Nao pode alterar proprio nivel

#### 6.2.2 Equipamentos (`/admin/equipamentos/`)

| Campo | Tipo | Obrigatorio | Validacao |
|-------|------|-------------|-----------|
| nome | text | Sim | - |
| slug | text | Auto | Unico |
| categoria_id | select | Nao | - |
| descricao | textarea | Nao | - |
| caracteristicas | textarea | Nao | Uma por linha |
| imagem | file | Nao | JPG/PNG/WebP, max 2MB |
| meta_title | text | Nao | Max 70 chars |
| meta_description | textarea | Nao | Max 160 chars |
| ordem | number | Nao | Default 0 |
| ativo | checkbox | Nao | Default 1 |

#### 6.2.3 Postes (`/admin/postes/`)

Mesma estrutura de Equipamentos, sem categoria.

#### 6.2.4 Blog (`/admin/blog/`)

| Campo | Tipo | Obrigatorio | Validacao |
|-------|------|-------------|-----------|
| titulo | text | Sim | - |
| slug | text | Auto | Unico |
| categoria_id | select | Nao | - |
| resumo | textarea | Nao | Max 300 chars |
| conteudo | richtext | Sim | Editor Quill |
| imagem_destaque | file | Nao | JPG/PNG/WebP |
| focus_keyword | text | Nao | Palavra-chave SEO |
| meta_title | text | Nao | Max 70 chars |
| meta_description | textarea | Nao | Max 160 chars |
| status | select | Sim | rascunho/publicado/agendado |
| publicado_em | datetime | Nao | Data de publicacao |

**Editor de Texto:**
- Biblioteca: Quill.js (CDN)
- Suporta: Negrito, italico, listas, links, imagens, codigo

**Analisador SEO:**
- Arquivo: `/admin/assets/js/seo-analyzer.js`
- Calcula score baseado em: titulo, descricao, keyword, conteudo

#### 6.2.5 Categorias de Equipamentos (`/admin/categorias-equip/`)

| Campo | Tipo | Obrigatorio |
|-------|------|-------------|
| nome | text | Sim |
| slug | text | Auto |
| descricao | textarea | Nao |
| ordem | number | Nao |
| ativo | checkbox | Nao |

**Restricao:** Nao pode excluir categoria com equipamentos vinculados.

#### 6.2.6 Categorias do Blog (`/admin/categorias-blog/`)

| Campo | Tipo | Obrigatorio |
|-------|------|-------------|
| nome | text | Sim |
| slug | text | Auto |
| descricao | textarea | Nao |
| cor | color | Nao |
| ativo | checkbox | Nao |

**Restricao:** Nao pode excluir categoria com posts vinculados.

#### 6.2.7 Depoimentos (`/admin/depoimentos/`)

| Campo | Tipo | Obrigatorio |
|-------|------|-------------|
| nome | text | Sim |
| texto | textarea | Sim |
| avaliacao | select | Nao (1-5) |
| data_depoimento | date | Nao |
| foto | file | Nao |
| ordem | number | Nao |
| ativo | checkbox | Nao |

---

## 7. Funcoes Helper

### 7.1 Banco de Dados (database.php)

```php
// Conexao
db(): PDO

// Consultas
fetchAll(string $table, string $where = '1=1', array $params = [], string $orderBy = 'id DESC'): array
fetchOne(string $table, string $where, array $params = []): ?array
fetchById(string $table, int $id): ?array
countRows(string $table, string $where = '1=1', array $params = []): int
query(string $sql, array $params = []): array  // SQL customizado

// Manipulacao
insert(string $table, array $data): int        // Retorna ID inserido
update(string $table, array $data, string $where, array $whereParams = []): int
delete(string $table, string $where, array $params = []): int
```

**Exemplos:**

```php
// Buscar todos equipamentos ativos
$equipamentos = fetchAll('equipamentos', 'ativo = 1', [], 'ordem ASC');

// Buscar usuario por email
$user = fetchOne('usuarios', 'email = ?', ['email@exemplo.com']);

// Buscar por ID
$post = fetchById('blog_posts', 123);

// Inserir novo registro
$id = insert('equipamentos', [
    'nome' => 'Betoneira',
    'slug' => 'betoneira',
    'ativo' => 1
]);

// Atualizar registro
update('equipamentos', ['nome' => 'Betoneira 400L'], 'id = ?', [123]);

// Excluir registro
delete('equipamentos', 'id = ?', [123]);

// Query customizada com JOIN
$posts = query("
    SELECT p.*, c.nome as categoria_nome
    FROM blog_posts p
    LEFT JOIN categorias_blog c ON p.categoria_id = c.id
    WHERE p.status = ?
", ['publicado']);
```

### 7.2 Utilitarios (functions.php)

```php
// URL e Navegacao
slugify(string $text): string              // Converte texto para slug
redirect(string $url): void                // Redireciona e encerra

// Seguranca
e(string $string): string                  // Escapa HTML (XSS)
generateCsrfToken(): string                // Gera token CSRF
validateCsrfToken(string $token): bool     // Valida token CSRF
csrfField(): string                        // Campo hidden com token
sanitize(string $input): string            // Limpa input

// Entrada de Dados
get(string $key, $default = null)          // GET sanitizado
post(string $key, $default = null)         // POST sanitizado

// Mensagens Flash
setFlash(string $type, string $message): void
getFlash(): ?array
showFlash(): string                        // Renderiza HTML

// Formatacao
formatDate(string $date, string $format = 'd/m/Y'): string
timeAgo(string $datetime): string          // "2 dias atras"
truncate(string $text, int $length = 100, string $suffix = '...'): string

// Upload
uploadImage(array $file, string $folder): ?string
deleteImage(string $path): bool
imageUrl(string $path): string

// Dados
getCaracteristicas($json): array           // Decode JSON

// SEO
metaTags(string $title, string $description = '', string $image = ''): string

// API
isAjax(): bool
jsonResponse(array $data, int $status = 200): void

// Validacao
isValidEmail(string $email): bool

// Utilitarios
getClientIp(): string
whatsappLink(string $message = null): string
```

**Exemplos:**

```php
// Escapar output
echo e($usuario['nome']);

// Formulario com CSRF
<form method="POST">
    <?= csrfField() ?>
    <input type="text" name="nome">
</form>

// Processar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken(post('csrf_token'))) {
        die('Token invalido');
    }
    $nome = post('nome');
}

// Mensagens flash
setFlash('success', 'Registro salvo com sucesso!');
redirect('/admin/equipamentos/');

// Na pagina de destino
<?= showFlash() ?>

// Upload de imagem
$imagem = uploadImage($_FILES['imagem'], 'equipamentos');
if ($imagem) {
    // Sucesso: $imagem = 'equipamentos/abc123_1234567890.jpg'
}

// Exibir imagem
<img src="<?= imageUrl($equipamento['imagem']) ?>">

// Tempo relativo
echo timeAgo('2024-01-15 10:30:00'); // "2 meses atras"

// Meta tags SEO
<?= metaTags($post['meta_title'], $post['meta_description'], $post['imagem_destaque']) ?>
```

---

## 8. Upload de Arquivos

### 8.1 Configuracao

```php
MAX_UPLOAD_SIZE = 2097152;  // 2MB
ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
```

### 8.2 Funcao uploadImage()

```php
function uploadImage(array $file, string $folder): ?string
```

**Parametros:**
- `$file`: Array `$_FILES['campo']`
- `$folder`: Pasta destino (equipamentos, postes, blog, depoimentos)

**Retorno:**
- Sucesso: Caminho relativo do arquivo (ex: `equipamentos/abc123_1234567890.jpg`)
- Erro: `null`

**Processo:**
1. Verifica erro de upload
2. Valida MIME type com `finfo`
3. Valida tamanho do arquivo
4. Gera nome unico: `uniqid()_timestamp.extensao`
5. Cria pasta se necessario
6. Move arquivo para `/uploads/{folder}/`

### 8.3 Pastas de Upload

```
/uploads/
├── equipamentos/    # Imagens de equipamentos
├── postes/          # Imagens de postes
├── blog/            # Imagens de posts
└── depoimentos/     # Fotos de clientes
```

### 8.4 Exemplo de Uso

```php
// No formulario
<input type="file" name="imagem" accept="image/*">

// No processamento
if (!empty($_FILES['imagem']['name'])) {
    $imagem = uploadImage($_FILES['imagem'], 'equipamentos');
    if ($imagem) {
        $data['imagem'] = $imagem;
    } else {
        $errors[] = 'Erro ao fazer upload da imagem.';
    }
}

// Para substituir imagem existente
if (!empty($_FILES['imagem']['name'])) {
    // Deleta imagem antiga
    if (!empty($registro['imagem'])) {
        deleteImage($registro['imagem']);
    }
    // Upload da nova
    $imagem = uploadImage($_FILES['imagem'], 'equipamentos');
}
```

---

## 9. Seguranca

### 9.1 Protecoes Implementadas

| Tipo | Implementacao |
|------|---------------|
| **SQL Injection** | PDO com prepared statements |
| **XSS** | Funcao `e()` para escape de HTML |
| **CSRF** | Tokens em formularios |
| **Senhas** | Hash bcrypt com cost 10 |
| **Upload** | Validacao de MIME type e tamanho |
| **Sessao** | Regeneracao de ID no login |
| **Arquivos** | .htaccess bloqueia acesso direto |

### 9.2 Headers de Seguranca (.htaccess)

```apache
Header set X-Frame-Options "SAMEORIGIN"
Header set X-Content-Type-Options "nosniff"
Header set X-XSS-Protection "1; mode=block"
Header set Referrer-Policy "strict-origin-when-cross-origin"
```

### 9.3 Arquivos Protegidos

```apache
# Bloqueio de arquivos sensiveis
<FilesMatch "\.(env|log|sql|bak)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Protege diretorios
<Directory "includes">
    Deny from all
</Directory>
```

### 9.4 Boas Praticas

```php
// SEMPRE escape output
echo e($variavel);

// SEMPRE use prepared statements
$user = fetchOne('usuarios', 'email = ?', [$email]);

// SEMPRE valide CSRF em formularios POST
if (!validateCsrfToken(post('csrf_token'))) {
    setFlash('error', 'Token invalido');
    redirect('/admin/');
}

// SEMPRE hash senhas
$hash = hashPassword($senha);

// NUNCA confie em input do usuario
$id = (int) get('id');  // Force tipo
$nome = sanitize(post('nome'));
```

---

## 10. Frontend

### 10.1 Paginas Publicas

| Arquivo | URL | Descricao |
|---------|-----|-----------|
| index.php | / | Pagina inicial |
| equipamentos.php | /equipamentos | Lista de equipamentos |
| equipamento.php | /equipamento/{slug} | Detalhe do equipamento |
| postes.php | /postes | Lista de postes |
| poste.php | /poste/{slug} | Detalhe do poste |
| blog.php | /blog | Lista de posts |
| post.php | /blog/{slug} | Detalhe do post |
| sobre.php | /sobre | Sobre a empresa |
| contato.php | /contato | Informacoes de contato |
| privacidade.php | /privacidade | Politica de privacidade |
| termos.php | /termos | Termos de uso |
| 404.php | - | Pagina de erro 404 |

### 10.2 URLs Amigaveis (.htaccess)

```apache
# Remover .php da URL
RewriteRule ^equipamento/([a-z0-9-]+)/?$ equipamento.php?slug=$1 [L,QSA]
RewriteRule ^poste/([a-z0-9-]+)/?$ poste.php?slug=$1 [L,QSA]
RewriteRule ^blog/([a-z0-9-]+)/?$ post.php?slug=$1 [L,QSA]
```

### 10.3 Componentes Reutilizaveis

```php
// Header com navegacao
<?php include 'includes/header.php'; ?>

// Footer
<?php include 'includes/footer.php'; ?>

// Botao WhatsApp flutuante
<?php include 'includes/whatsapp-button.php'; ?>

// CTA final
<?php include 'includes/cta-final.php'; ?>
```

### 10.4 Meta Tags SEO

```php
<?php
$title = $post['meta_title'] ?: $post['titulo'];
$description = $post['meta_description'] ?: $post['resumo'];
$image = $post['imagem_destaque'] ? imageUrl($post['imagem_destaque']) : '';

echo metaTags($title, $description, $image);
?>
```

---

## Apendice A: SQL de Instalacao

Execute o arquivo `/database/schema.sql` para criar todas as tabelas e dados iniciais.

```bash
mysql -u root -p construbao < database/schema.sql
```

## Apendice B: Checklist de Deploy

- [ ] Copiar `.env.example` para `.env`
- [ ] Configurar credenciais do banco em `.env`
- [ ] Executar `schema.sql` no banco
- [ ] Configurar permissoes da pasta `/uploads/` (755)
- [ ] Alterar `APP_ENV` para `production`
- [ ] Alterar `APP_DEBUG` para `false`
- [ ] Alterar senha do usuario admin
- [ ] Verificar se mod_rewrite esta ativo
- [ ] Deletar arquivo `gerar-hash.php` se existir

## Apendice C: Troubleshooting

**Erro: "Arquivo .env nao encontrado"**
- Copie `.env.example` para `.env` e configure

**Erro: "SQLSTATE Connection refused"**
- Verifique credenciais do banco em `.env`
- Verifique se MySQL esta rodando

**Erro: "Erro ao fazer upload"**
- Verifique permissoes da pasta `/uploads/`
- Verifique `MAX_UPLOAD_SIZE` no `.env` e `php.ini`

**Erro: "Token CSRF invalido"**
- Sessao pode ter expirado, faca login novamente
- Verifique se `session_start()` esta sendo chamado

**Imagens nao aparecem**
- Verifique se `SITE_URL` esta correto
- Verifique se arquivos existem em `/uploads/`

---

*Documentacao gerada em 19/12/2024*
