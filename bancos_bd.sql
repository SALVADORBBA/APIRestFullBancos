# Host: localhost  (Version 5.5.5-10.4.27-MariaDB)
# Date: 2023-08-19 09:12:53
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "bancos_modulos"
#

CREATE TABLE `bancos_modulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(255) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 2,
  `logo` text DEFAULT NULL,
  `ambiente` int(11) DEFAULT 2,
  `apelido` varchar(255) DEFAULT NULL,
  `system_unit_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=761 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

#
# Structure for table "beneficiario"
#

CREATE TABLE `beneficiario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cnpj` varchar(20) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `cep` varchar(20) DEFAULT NULL,
  `fantasia` varchar(100) DEFAULT NULL,
  `razao` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `cuf` int(11) DEFAULT NULL,
  `ccidade` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `system_unit_id` int(11) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `lng` varchar(255) DEFAULT NULL,
  `token_api` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

#
# Structure for table "cliente"
#

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `documento` varchar(18) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `cep` varchar(255) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `codigo_ibge` int(11) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `system_unit_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `custom_id` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Structure for table "cobranca_titulo"
#

CREATE TABLE `cobranca_titulo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `beneficiario_id` int(11) DEFAULT NULL,
  `system_unit_id` int(11) DEFAULT NULL,
  `parametros_bancos_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `cobranca_id` int(11) DEFAULT NULL,
  `valor` double DEFAULT NULL,
  `data_vencimento` date DEFAULT NULL,
  `novaDataVencimento` date DEFAULT NULL,
  `xml_nfe` text DEFAULT NULL,
  `emissao_tipo` int(11) DEFAULT 1,
  `bancos_modulos_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'new',
  `carne_id` int(11) DEFAULT NULL,
  `tipo` int(11) DEFAULT 1,
  `parcelas` varchar(10) DEFAULT NULL,
  `numero_parcelas` varchar(20) DEFAULT NULL,
  `identificacaoboletoempresa` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `valorabatimento` double(10,2) DEFAULT NULL,
  `seunumero` varchar(255) DEFAULT NULL,
  `caminho_boleto` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `data_baixa` datetime DEFAULT NULL,
  `descricao_baixa` text DEFAULT NULL,
  `numero_bb` varchar(255) DEFAULT NULL,
  `DataDoProces` datetime DEFAULT NULL,
  `qrcode` text DEFAULT NULL,
  `linhadigitavel` varchar(255) DEFAULT NULL,
  `codigobarras` varchar(255) DEFAULT NULL,
  `digito_verificador_global` varchar(11) DEFAULT NULL,
  `indentificacao_global` varchar(100) DEFAULT NULL,
  `modelo` int(11) DEFAULT NULL,
  `numero_generico_1` varchar(255) DEFAULT NULL,
  `numero_generico_2` varchar(255) DEFAULT NULL,
  `ambiente_emissao` varchar(50) DEFAULT '',
  `etapa_processo_boleto` varchar(50) DEFAULT 'validacao',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

#
# Structure for table "controle_meu_numeros"
#

CREATE TABLE `controle_meu_numeros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parametros_bancos_id` int(11) DEFAULT NULL,
  `ultimo_numero` int(11) DEFAULT NULL,
  `numero_anterior` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `status` varchar(20) DEFAULT 'livre',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=858 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Structure for table "eventos_boletos"
#

CREATE TABLE `eventos_boletos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `linhaDigitavel` varchar(255) DEFAULT NULL,
  `codigoBarras` varchar(255) DEFAULT NULL,
  `caminho_pdf` text DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT current_timestamp(),
  `parametros_bancos_id` int(11) DEFAULT NULL,
  `system_unit_id` int(11) DEFAULT NULL,
  `documento_id` varchar(255) DEFAULT NULL,
  `mensagem` text DEFAULT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `qrCode` text DEFAULT NULL,
  `txid` varchar(255) DEFAULT NULL,
  `cobranca_titulo_id` int(11) DEFAULT NULL,
  `url_banco` text DEFAULT NULL,
  `numerocontratocobranca` varchar(50) DEFAULT NULL,
  `codigocliente` varchar(50) DEFAULT NULL,
  `numerocarteira` varchar(50) DEFAULT NULL,
  `numerovariacaocarteira` varchar(50) DEFAULT NULL,
  `seunumero` varchar(30) DEFAULT NULL,
  `caminho_boleto` varchar(255) DEFAULT NULL,
  `nosso_numero_banco` varchar(255) DEFAULT NULL,
  `print` varchar(10) DEFAULT 'no',
  `titulo` varchar(255) DEFAULT 'Create',
  `user_id` int(11) DEFAULT NULL,
  `prorrogacao_data` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=809 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Structure for table "layout_bancos"
#

CREATE TABLE `layout_bancos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `bancos_modulos_id` int(11) DEFAULT NULL,
  `logomarca` varchar(255) DEFAULT NULL,
  `codigo_layout` varchar(255) DEFAULT NULL,
  `tipo_layout` varchar(50) DEFAULT NULL,
  `nome_arquivo_php` varchar(255) DEFAULT NULL,
  `nome_arquivo_css` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `imagem_layout` text DEFAULT NULL,
  `modelo_id` int(11) DEFAULT NULL,
  `bancos` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Structure for table "mill_boletos"
#

CREATE TABLE `mill_boletos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cobranca_id` int(11) DEFAULT NULL,
  `nossoNumero` varchar(200) DEFAULT NULL,
  `codigoBarras` varchar(200) DEFAULT NULL,
  `linhaDigitavel` varchar(200) DEFAULT NULL,
  `pdfBoleto` text DEFAULT NULL,
  `pdf_nfe` text DEFAULT NULL,
  `status` varchar(200) NOT NULL DEFAULT 'Em Aberto',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `valor` varchar(50) NOT NULL,
  `data_vencimento` varchar(50) NOT NULL,
  `dataEmissao` varchar(50) DEFAULT NULL,
  `xml` text DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `datavencimento_interno` date DEFAULT NULL,
  `data_baixa` datetime DEFAULT NULL,
  `mensagem_baixa` varchar(255) DEFAULT NULL,
  `mill_parametros_id` int(11) DEFAULT NULL,
  `system_unit_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=559 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Structure for table "log_boletos"
#

CREATE TABLE `log_boletos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataHistorico` varchar(100) DEFAULT NULL,
  `tipoHistorico` varchar(10) DEFAULT NULL,
  `descricaoHistorico` varchar(255) DEFAULT NULL,
  `data_processamento` datetime DEFAULT NULL,
  `criacao_date` datetime DEFAULT current_timestamp(),
  `mill_sicoob_boletos_id` int(11) NOT NULL,
  `datavencimento_interno` date DEFAULT NULL,
  `data_criacao_boleto` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mill_log_boletos_1` (`mill_sicoob_boletos_id`),
  CONSTRAINT `fk_mill_log_boletos_1` FOREIGN KEY (`mill_sicoob_boletos_id`) REFERENCES `mill_boletos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Structure for table "parametros_bancos"
#

CREATE TABLE `parametros_bancos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numerocontacorrente` varchar(100) NOT NULL DEFAULT '',
  `digito_conta` int(11) DEFAULT NULL,
  `agencia` varchar(20) DEFAULT NULL,
  `digito_agencia` int(11) DEFAULT NULL,
  `beneficiario_id` int(11) DEFAULT NULL,
  `system_unit_id` int(11) DEFAULT NULL,
  `bancos_modulos_id` int(11) DEFAULT NULL,
  `tipos_documentos_id` varchar(100) DEFAULT '2' COMMENT 'Espécie do Documento. Informar valores listados abaixo',
  `numerocontrato` varchar(100) NOT NULL DEFAULT '',
  `certificado` text DEFAULT NULL,
  `senha` varchar(200) DEFAULT NULL,
  `modalidade` int(11) DEFAULT 1 COMMENT 'Número que identifica a modalidade do boleto. Infomar',
  `identificacaoboletoempresa` int(10) DEFAULT 0,
  `identificacaoemissaoboleto` varchar(100) DEFAULT '',
  `identificacaodistribuicaoboleto` varchar(100) DEFAULT '',
  `tipodesconto` varchar(50) DEFAULT '' COMMENT 'Informar o tipo de desconto atribuido ao boleto.',
  `diasparadesconto_primeiro` int(11) DEFAULT 1,
  `valorprimeirodesconto` double(10,2) DEFAULT NULL COMMENT 'example: 3',
  `dataprimeirodesconto` date DEFAULT NULL COMMENT 'example: 2018-09-20T00:00:00-03:00',
  `diasparadesconto_segundo` int(11) DEFAULT NULL COMMENT 'example: 8',
  `datasegundodesconto` date DEFAULT NULL COMMENT 'example: 2018-09-20T00:00:00-03:00',
  `valorsegundodesconto` double(10,2) DEFAULT NULL COMMENT 'example: 10',
  `diasparadesconto_terceiro` varchar(255) DEFAULT NULL COMMENT 'example: 9',
  `dataterceirodesconto` date DEFAULT NULL COMMENT 'example: 2018-09-20T00:00:00-03:00',
  `valorTerceiroDesconto` double(10,2) DEFAULT NULL COMMENT 'example: 1',
  `tipomulta` varchar(50) DEFAULT '0',
  `tipojurosmora` varchar(50) DEFAULT '0',
  `diasmultas` int(11) DEFAULT 5,
  `valormulta` int(11) DEFAULT 0,
  `diasjurosmora` int(11) DEFAULT 0,
  `valorjurosmora` int(11) DEFAULT 0,
  `codigoprotesto` varchar(11) DEFAULT '50',
  `numerodiasprotesto` int(11) DEFAULT 0,
  `codigonegativacao` varchar(50) DEFAULT NULL,
  `numerodiasnegativacao` int(11) DEFAULT NULL,
  `gerarpdf` varchar(255) DEFAULT 'true' COMMENT 'example: false ou true',
  `ambiente` int(11) DEFAULT 2,
  `client_id` varchar(255) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `mill_scope_sicoob_id` varchar(50) DEFAULT NULL,
  `emissor_certificado` varchar(255) DEFAULT NULL,
  `emissao_certificado` datetime DEFAULT NULL,
  `proprietario_certificado` varchar(255) DEFAULT NULL,
  `validade_certificado` datetime DEFAULT NULL,
  `validar_certificado` int(11) DEFAULT 0,
  `campo_generico` varchar(255) DEFAULT NULL,
  `global_1` varchar(255) DEFAULT NULL,
  `global_2` varchar(255) DEFAULT NULL,
  `global_3` varchar(255) DEFAULT NULL,
  `global_4` varchar(255) DEFAULT NULL,
  `global_5` varchar(255) DEFAULT NULL,
  `global_6` varchar(255) DEFAULT NULL,
  `global_7` varchar(255) DEFAULT NULL,
  `global_8` varchar(255) DEFAULT NULL,
  `global_9` text DEFAULT NULL,
  `global_10` varchar(255) DEFAULT NULL,
  `info1` varchar(100) DEFAULT NULL,
  `info2` varchar(255) DEFAULT NULL,
  `info3` varchar(255) DEFAULT NULL,
  `info4` varchar(255) DEFAULT NULL,
  `info5` varchar(255) DEFAULT NULL,
  `mens1` varchar(255) DEFAULT NULL,
  `mens2` varchar(255) DEFAULT NULL,
  `mens3` varchar(255) DEFAULT NULL,
  `mens4` varchar(255) DEFAULT NULL,
  `token_api_local` text DEFAULT NULL,
  `login_api` varchar(255) DEFAULT NULL,
  `senha_api` varchar(255) DEFAULT NULL,
  `orgaonegativador` varchar(255) DEFAULT NULL,
  `abatimento` varchar(20) DEFAULT NULL,
  `chave_1` text DEFAULT NULL,
  `chave_2` text DEFAULT NULL,
  `chave_3` text DEFAULT NULL,
  `chave_4` text DEFAULT NULL,
  `api_endpoint_url_homologacao` text DEFAULT NULL,
  `api_endpoint_url_producao` text DEFAULT NULL,
  `client_secret` text DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `mill_templates_email_id` int(11) DEFAULT NULL,
  `modelo_id` int(11) DEFAULT NULL,
  `apelido` varchar(20) DEFAULT NULL,
  `layout_banco_id` int(11) DEFAULT NULL,
  `chave_pix` text DEFAULT NULL,
  `tipo_chave_pix` varchar(50) DEFAULT NULL,
  `client_id_bolecode` text DEFAULT NULL,
  `client_secret_bolecode` text DEFAULT NULL,
  `certificados_pix` varchar(255) DEFAULT NULL,
  `certificados_extra` varchar(255) DEFAULT NULL,
  `senha_certificado_pix` varchar(255) DEFAULT NULL,
  `senha_certificado_extra` varchar(255) DEFAULT NULL,
  `carteira` varchar(50) DEFAULT NULL,
  `certificado_base64` longtext DEFAULT NULL,
  `certificado_pix_base64` longtext DEFAULT NULL,
  `certificado_extra_base64` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

#
# Structure for table "status"
#

CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chave` varchar(50) DEFAULT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
