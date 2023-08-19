# Host: millgest.com.br  (Version 5.7.23-23)
# Date: 2023-08-18 21:06:25
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "mill_layout_bancos"
#

CREATE TABLE `mill_layout_bancos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mill_bancos_modulos_id` int(11) DEFAULT NULL,
  `logomarca` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_layout` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_layout` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome_arquivo_php` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome_arquivo_css` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `imagem_layout` text COLLATE utf8_unicode_ci,
  `modelo_id` int(11) DEFAULT NULL,
  `bancos` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_mill_layout_bancos_1` (`mill_bancos_modulos_id`),
  KEY `fk_mill_layout_bancos_2` (`modelo_id`),
  CONSTRAINT `fk_mill_layout_bancos_1` FOREIGN KEY (`mill_bancos_modulos_id`) REFERENCES `mill_bancos_modulos` (`id`),
  CONSTRAINT `fk_mill_layout_bancos_2` FOREIGN KEY (`modelo_id`) REFERENCES `mill_modelo_boleto` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "mill_layout_bancos"
#

INSERT INTO `mill_layout_bancos` VALUES (1,' Banco do Brasil',1,'logomarca/1/Screenshot_23.jpg','B0001','HIBRIDO',NULL,NULL,'1','imagem_lyout/1/M00001.jpg',2,NULL),(2,'Banco do Brasil',1,'logomarca/2/Screenshot_23.jpg','B0002','NORMAL','BoletoBB002.php','style_bb.css','2','imagem_lyout/2/M0002.jpg',1,NULL),(3,'Layout Caixa',33,'logomarca/3/Screenshot_24.jpg','C0001','NORMAL',NULL,NULL,'1',NULL,1,NULL),(4,'Layout Itau',118,'logomarca/4/itau.jpg','ITA001','NORMAL','Layout Itau Normal',NULL,'1','imagem_lyout/4/modelo.jpg',1,NULL),(5,'Layout  BANRISUL',758,'logomarca/5/Screenshot_6.jpg','BANRISUL','HIBRIDO','BANRISUL','BANRISUL','1','imagem_lyout/5/Boletos-Ficha-Compensacao.png',2,NULL),(6,'Layout Itau',118,'logomarca/6/logo-tau.jpg','ITA001','NORMAL','Layout Itau Hibrido',NULL,'1','imagem_lyout/4/modelo.jpg',2,NULL);
