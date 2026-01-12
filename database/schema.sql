-- =====================================================
-- CONSTRUBÃO - Schema do Banco de Dados
-- Execute este arquivo no MySQL para criar as tabelas
-- =====================================================

-- Criar banco de dados (se não existir)
CREATE DATABASE IF NOT EXISTS construbao CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE construbao;

-- =====================================================
-- TABELA: usuarios
-- Usuários do painel administrativo
-- =====================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nivel ENUM('admin', 'editor') DEFAULT 'editor',
    ativo TINYINT(1) DEFAULT 1,
    api_key VARCHAR(64) NULL UNIQUE,
    ultimo_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_ativo (ativo),
    INDEX idx_api_key (api_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: categorias_equipamentos
-- Categorias para equipamentos
-- =====================================================
CREATE TABLE IF NOT EXISTS categorias_equipamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    descricao TEXT NULL,
    icone VARCHAR(50) NULL,
    ordem INT DEFAULT 0,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_ativo (ativo),
    INDEX idx_ordem (ordem)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: equipamentos
-- Equipamentos para locação
-- =====================================================
CREATE TABLE IF NOT EXISTS equipamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NULL,
    nome VARCHAR(150) NOT NULL,
    slug VARCHAR(150) UNIQUE NOT NULL,
    descricao TEXT NULL,
    caracteristicas JSON NULL,
    imagem VARCHAR(255) NULL,
    meta_title VARCHAR(70) NULL,
    meta_description VARCHAR(160) NULL,
    ativo TINYINT(1) DEFAULT 1,
    destaque TINYINT(1) DEFAULT 0,
    ordem INT DEFAULT 0,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias_equipamentos(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_ativo (ativo),
    INDEX idx_destaque (destaque),
    INDEX idx_ordem (ordem)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: postes
-- Postes padrão para venda
-- =====================================================
CREATE TABLE IF NOT EXISTS postes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    slug VARCHAR(150) UNIQUE NOT NULL,
    descricao TEXT NULL,
    caracteristicas JSON NULL,
    imagem VARCHAR(255) NULL,
    meta_title VARCHAR(70) NULL,
    meta_description VARCHAR(160) NULL,
    ativo TINYINT(1) DEFAULT 1,
    ordem INT DEFAULT 0,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_ativo (ativo),
    INDEX idx_ordem (ordem)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: categorias_blog
-- Categorias para posts do blog
-- =====================================================
CREATE TABLE IF NOT EXISTS categorias_blog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    descricao TEXT NULL,
    cor VARCHAR(7) DEFAULT '#FFBA00',
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: blog_posts
-- Posts do blog com campos SEO
-- =====================================================
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NULL,
    autor_id INT NULL,
    titulo VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    resumo VARCHAR(300) NULL,
    conteudo LONGTEXT NULL,
    imagem_destaque VARCHAR(255) NULL,

    -- Campos SEO (estilo RankMath)
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
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (categoria_id) REFERENCES categorias_blog(id) ON DELETE SET NULL,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_publicado (publicado_em),
    INDEX idx_categoria (categoria_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: depoimentos
-- Depoimentos de clientes para o carrossel
-- =====================================================
CREATE TABLE IF NOT EXISTS depoimentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    foto VARCHAR(255) NULL,
    texto TEXT NOT NULL,
    avaliacao INT DEFAULT 5,
    data_depoimento DATE NULL,
    ativo TINYINT(1) DEFAULT 1,
    ordem INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_ativo (ativo),
    INDEX idx_ordem (ordem)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: configuracoes
-- Configurações gerais do site
-- =====================================================
CREATE TABLE IF NOT EXISTS configuracoes (
    chave VARCHAR(50) PRIMARY KEY,
    valor TEXT NULL,
    descricao VARCHAR(255) NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DADOS INICIAIS
-- =====================================================

-- Usuário admin padrão (senha: admin123)
INSERT INTO usuarios (nome, email, senha, nivel, ativo) VALUES
('Administrador', 'admin@construbao.com.br', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);

-- Categorias de Equipamentos
INSERT INTO categorias_equipamentos (nome, slug, descricao, ordem, ativo) VALUES
('Preparação de Solo', 'preparacao-solo', 'Equipamentos para preparação e compactação de solo', 1, 1),
('Concreto e Argamassa', 'concreto-argamassa', 'Equipamentos para mistura e transporte de concreto', 2, 1),
('Demolição', 'demolicao', 'Equipamentos para demolição e rompimento', 3, 1),
('Corte', 'corte', 'Equipamentos para corte de piso e materiais', 4, 1),
('Energia', 'energia', 'Geradores e compressores', 5, 1),
('Estrutura', 'estrutura', 'Escoras, andaimes e equipamentos de apoio', 6, 1),
('Limpeza', 'limpeza', 'Aspiradores e equipamentos de limpeza', 7, 1),
('Segurança', 'seguranca', 'Equipamentos de segurança para obras', 8, 1);

-- Equipamentos
INSERT INTO equipamentos (categoria_id, nome, slug, descricao, caracteristicas, imagem, ativo, ordem) VALUES
(2, 'Betoneiras', 'betoneiras', 'Betoneiras de alta qualidade para mistura de concreto, argamassa e outros materiais. Ideais para obras de pequeno a médio porte.', '["Capacidade variada", "Motor elétrico ou a gasolina", "Fácil operação", "Manutenção em dia"]', 'assets/images/equip-betoneira.png', 1, 1),
(1, 'Compactadores', 'compactadores', 'Compactadores de solo para preparação de terrenos, valas e pisos. Essenciais para uma base sólida na construção.', '["Alta força de impacto", "Compactação eficiente", "Motor potente", "Fácil manuseio"]', 'assets/images/equip-compactador.png', 1, 2),
(4, 'Cortadores de Piso', 'cortadores-de-piso', 'Cortadores de piso para cortes precisos em concreto, asfalto e pavimentos. Perfeitos para obras de infraestrutura.', '["Corte preciso", "Disco diamantado", "Sistema de refrigeração", "Alta durabilidade"]', 'assets/images/equip-cortador-piso.png', 1, 3),
(1, 'Placas Vibratórias', 'placas-vibratorias', 'Placas vibratórias para compactação de solos granulares, pavimentos e áreas de difícil acesso.', '["Vibração de alta frequência", "Compactação uniforme", "Leve e portátil", "Ideal para áreas restritas"]', 'assets/images/equip-placa-vibratoria.png', 1, 4),
(3, 'Rompedores', 'rompedores', 'Rompedores elétricos e pneumáticos para demolição de concreto, rochas e estruturas. Potência para trabalhos pesados.', '["Alta potência de impacto", "Elétrico ou pneumático", "Ponteiras intercambiáveis", "Ergonômico"]', 'assets/images/equip-rompedor.png', 1, 5),
(5, 'Geradores', 'geradores', 'Geradores de energia para alimentação de equipamentos e ferramentas em locais sem rede elétrica.', '["Diversas potências", "Motor a gasolina ou diesel", "Partida elétrica", "Silenciosos"]', 'assets/images/equip-gerador.png', 1, 6),
(5, 'Compressores de Ar', 'compressores-de-ar', 'Compressores de ar para alimentação de ferramentas pneumáticas e diversas aplicações industriais.', '["Alta pressão", "Tanque reservatório", "Motor potente", "Baixa manutenção"]', 'assets/images/equip-compressor.png', 1, 7),
(7, 'Aspiradores Hidropó', 'aspiradores-hidropo', 'Aspiradores industriais para limpeza pesada de resíduos sólidos e líquidos em obras e indústrias.', '["Aspira sólidos e líquidos", "Alta capacidade", "Filtros laváveis", "Rodas para transporte"]', 'assets/images/equip-aspirador.png', 1, 8),
(6, 'Escoras 6,0m', 'escoras-6m', 'Escoras metálicas reguláveis de 6 metros para escoramento de lajes e estruturas em grandes alturas.', '["Altura regulável", "Aço galvanizado", "Alta resistência", "Rosca de ajuste fino"]', 'assets/images/equip-escora-6m.png', 1, 9),
(6, 'Escoras 3,0m', 'escoras-3m', 'Escoras metálicas reguláveis de 3 metros para escoramento de lajes e estruturas em alturas convencionais.', '["Altura regulável", "Aço galvanizado", "Alta resistência", "Fácil instalação"]', 'assets/images/equip-escora-3m.png', 1, 10),
(6, 'Andaimes', 'andaimes', 'Andaimes tubulares para trabalhos em altura com segurança. Estrutura modular e fácil montagem.', '["Estrutura modular", "Fácil montagem", "Certificado de segurança", "Plataformas antiderrapantes"]', 'assets/images/equip-andaime.png', 1, 11),
(6, 'Escadas', 'escadas', 'Escadas de fibra e alumínio em diversos tamanhos para acesso seguro a diferentes alturas.', '["Fibra ou alumínio", "Diversos tamanhos", "Antiderrapantes", "Leves e resistentes"]', 'assets/images/equip-escada.png', 1, 12),
(8, 'Guarda Corpo', 'guarda-corpo', 'Sistemas de guarda corpo para proteção em bordas de lajes e áreas de risco. Segurança conforme normas.', '["Conforme NR-18", "Fácil instalação", "Aço galvanizado", "Reutilizável"]', 'assets/images/equip-guarda-corpo.png', 1, 13);

-- Postes
INSERT INTO postes (nome, slug, descricao, caracteristicas, imagem, ativo, ordem) VALUES
('Poste Frontal', 'poste-frontal', 'Poste padrão com entrada frontal, ideal para residências com recuo. Homologado pela CPFL, atende todas as normas técnicas vigentes.', '["Entrada frontal", "Homologado CPFL", "Ideal para residências com recuo", "Concreto armado de alta resistência"]', 'assets/images/poste-frontal.png', 1, 1),
('Poste Lateral', 'poste-lateral', 'Poste padrão com entrada lateral, ideal para terrenos sem recuo. Homologado pela CPFL, perfeito para construções na divisa.', '["Entrada lateral", "Homologado CPFL", "Ideal para terrenos sem recuo", "Instalação na divisa"]', 'assets/images/poste-lateral.png', 1, 2),
('Poste Lateral ou Frontal', 'poste-lateral-frontal', 'Poste padrão com duas caixas de medição, ideal para terrenos com duas unidades consumidoras. Versátil e econômico.', '["Duas caixas de medição", "Entrada lateral e frontal", "Ideal para duas unidades", "Homologado CPFL"]', 'assets/images/poste-lateral-frontal.png', 1, 3),
('Poste Convencional', 'poste-convencional', 'Poste de concreto duplo T para instalações que requerem maior resistência mecânica. Indicado para cargas pesadas.', '["Concreto duplo T", "Maior resistência mecânica", "Para cargas pesadas", "Alta durabilidade"]', 'assets/images/poste-convencional.png', 1, 4);

-- Categorias do Blog
INSERT INTO categorias_blog (nome, slug, descricao, cor, ativo) VALUES
('Poste Padrão', 'poste-padrao', 'Artigos sobre postes padrão e instalação elétrica', '#FFBA00', 1),
('Equipamentos', 'equipamentos', 'Dicas sobre equipamentos para construção', '#0A2A3F', 1),
('Dicas de Obra', 'dicas-de-obra', 'Dicas práticas para sua obra', '#FF9933', 1),
('Segurança', 'seguranca', 'Artigos sobre segurança no trabalho', '#E74C3C', 1);

-- Depoimentos
INSERT INTO depoimentos (nome, foto, texto, avaliacao, data_depoimento, ativo, ordem) VALUES
('Amilton Silva', 'assets/images/depoimento-amilton.png', 'Excelente atendimento! Aluguei uma betoneira e o equipamento estava em perfeito estado. Recomendo muito a Construbão para todos que precisam de equipamentos de qualidade.', 5, '2024-01-15', 1, 1),
('Eduardo Santos', 'assets/images/depoimento-eduardo.png', 'Comprei o poste padrão e fiquei muito satisfeito. Entrega rápida e preço justo. A equipe é muito profissional e prestativa.', 5, '2024-02-20', 1, 2),
('Igor Mendes', 'assets/images/depoimento-igor.png', 'Já aluguei diversos equipamentos na Construbão. Sempre tudo funcionando perfeitamente. São parceiros de confiança para minha construtora.', 5, '2024-03-10', 1, 3),
('Jussara Lima', 'assets/images/depoimento-jussara.png', 'Atendimento nota 10! Precisei de uma escora urgente e eles resolveram rapidamente. Preço competitivo e qualidade garantida.', 5, '2024-04-05', 1, 4),
('Luiz Fernando', 'assets/images/depoimento-luiz.png', 'Empresa séria e comprometida. Os equipamentos são de primeira linha e a manutenção é impecável. Indico para todos!', 5, '2024-05-18', 1, 5),
('Marcos Oliveira', 'assets/images/depoimento-marcos.png', 'Melhor lugar para alugar equipamentos em São Carlos! Preços justos, equipamentos novos e atendimento excepcional.', 5, '2024-06-22', 1, 6);

-- Configurações do site
INSERT INTO configuracoes (chave, valor, descricao) VALUES
('site_nome', 'Construbão', 'Nome do site'),
('site_descricao', 'Venda de Poste Padrão e Locação de Equipamentos para Construção', 'Descrição do site'),
('telefone', '(16) 99700-7775', 'Telefone principal'),
('email', 'contato@construbao.com.br', 'E-mail de contato'),
('endereco', 'Rod. Washington Luiz, Km 230 - São Carlos/SP', 'Endereço'),
('horario', 'Seg-Sex: 8h às 18h | Sáb: 8h às 12h', 'Horário de funcionamento'),
('instagram', 'https://instagram.com/construbao', 'Link do Instagram'),
('facebook', 'https://facebook.com/construbao', 'Link do Facebook'),
('whatsapp', '5516997007775', 'Número do WhatsApp'),
('google_maps', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3699.0!2d-47.89!3d-22.01!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjLCsDAwJzM2LjAiUyA0N8KwNTMnMjQuMCJX!5e0!3m2!1spt-BR!2sbr!4v1234567890', 'Embed do Google Maps');
