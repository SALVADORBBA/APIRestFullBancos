# Host: millgest.com.br  (Version 5.7.23-23)
# Date: 2023-08-18 21:05:50
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "baseglobal"
#

CREATE TABLE `baseglobal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url_banco_layout` text COLLATE utf8_unicode_ci,
  `creditos` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instrucoes` text COLLATE utf8_unicode_ci,
  `url_api` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "baseglobal"
#

INSERT INTO `baseglobal` VALUES (1,'https://apicobranca.millgest.com.br/','Millennium Softwares de Gestão e ZAPBRASIL TELECOM',' ATENÇÃO BOLETO EM HOMOLOGAÇÃO NÃO PAGUE, SENHOR CAIXA NÃO ACEITE.','https://apicobranca.millgest.com.br/apirestfull/public/');
