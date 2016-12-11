-- MySQL dump 10.13  Distrib 5.6.30, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: b8admin
-- ------------------------------------------------------
-- Server version	5.6.30-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `sdk_admlogin`
--

DROP TABLE IF EXISTS `sdk_admlogin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sdk_admlogin` (
  `dt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dt_hash` varchar(30) CHARACTER SET latin1 NOT NULL,
  `dt_usuario` varchar(100) CHARACTER SET latin1 NOT NULL,
  `dt_senha` varchar(60) CHARACTER SET latin1 NOT NULL,
  `dt_admin` int(11) DEFAULT NULL,
  `dt_acessos` text CHARACTER SET latin1,
  `dt_data_inicio` int(11) DEFAULT NULL,
  `dt_data_fim` int(11) DEFAULT NULL,
  `dt_ativado` int(11) DEFAULT NULL,
  `dt_criado_por` bigint(20) DEFAULT NULL,
  `dt_criado_data` int(11) DEFAULT NULL,
  `dt_alterado_data` int(11) DEFAULT NULL,
  `dt_corpo` text CHARACTER SET latin1,
  `dt_campo1` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_campo2` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_campo3` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_hits` int(11) DEFAULT NULL,
  PRIMARY KEY (`dt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sdk_admlogin`
--

LOCK TABLES `sdk_admlogin` WRITE;
/*!40000 ALTER TABLE `sdk_admlogin` DISABLE KEYS */;
INSERT INTO `sdk_admlogin` VALUES (1,'DJmJJKLMiOPa','bruno','$2a$08$Q5FdPF1YtlEuXG/dVO7v5.yqD3b4CrSZnlzsqYi9DBIGdmEzg0M9W',1,'1440441916,1453915487,1453931462,1453980839,1454020330,1454067036,1454152975,1454176735,1454211879,1454350571,1454431358,1457823914,1457842661,1457881396,1457889595,1458497420,1458996084,1462489275,1463545258,1476746564,1481466498',NULL,NULL,1,NULL,NULL,1458497494,'','Bruno','','1,2,7,3,14,15,8,9,10',NULL);
/*!40000 ALTER TABLE `sdk_admlogin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sdk_admsettings`
--

DROP TABLE IF EXISTS `sdk_admsettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sdk_admsettings` (
  `dt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dt_email_addr` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_email_name` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_language` int(11) DEFAULT NULL,
  PRIMARY KEY (`dt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sdk_admsettings`
--

LOCK TABLES `sdk_admsettings` WRITE;
/*!40000 ALTER TABLE `sdk_admsettings` DISABLE KEYS */;
INSERT INTO `sdk_admsettings` VALUES (1,'','',0);
/*!40000 ALTER TABLE `sdk_admsettings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sdk_cadastrados`
--

DROP TABLE IF EXISTS `sdk_cadastrados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sdk_cadastrados` (
  `dt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dt_hash` varchar(30) CHARACTER SET latin1 NOT NULL,
  `dt_usuario` varchar(100) CHARACTER SET latin1 NOT NULL,
  `dt_senha` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `dt_tipo` int(11) DEFAULT NULL,
  `dt_acessos` text CHARACTER SET latin1,
  `dt_categoria` int(11) DEFAULT NULL,
  `dt_data_inicio` int(11) DEFAULT NULL,
  `dt_data_fim` int(11) DEFAULT NULL,
  `dt_ativado` int(11) DEFAULT NULL,
  `dt_criado_por` bigint(20) DEFAULT NULL,
  `dt_criado_data` int(11) DEFAULT NULL,
  `dt_alterado_data` int(11) DEFAULT NULL,
  `dt_corpo` text CHARACTER SET latin1,
  `dt_campo1` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_campo2` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_campo3` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_hits` int(11) DEFAULT NULL,
  PRIMARY KEY (`dt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sdk_cadastrados`
--

LOCK TABLES `sdk_cadastrados` WRITE;
/*!40000 ALTER TABLE `sdk_cadastrados` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdk_cadastrados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sdk_categorias`
--

DROP TABLE IF EXISTS `sdk_categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sdk_categorias` (
  `dt_cat_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dt_cat_hash` varchar(30) CHARACTER SET latin1 NOT NULL,
  `dt_cat_pai` bigint(20) DEFAULT NULL,
  `dt_cat_titulo` varchar(300) CHARACTER SET latin1 NOT NULL,
  `dt_cat_alias` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `dt_cat_autor` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `dt_cat_data_inicio` int(11) DEFAULT NULL,
  `dt_cat_data_fim` int(11) DEFAULT NULL,
  `dt_cat_ativado` int(11) DEFAULT NULL,
  `dt_cat_destaque` int(11) DEFAULT NULL,
  `dt_cat_criado_por` bigint(20) DEFAULT NULL,
  `dt_cat_criado_data` int(11) DEFAULT NULL,
  `dt_cat_alterado_data` int(11) DEFAULT NULL,
  `dt_cat_corpo` text CHARACTER SET latin1,
  `dt_galeria` text,
  `dt_cat_meta_descricao` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_cat_meta_tags` text CHARACTER SET latin1,
  `dt_cat_hits` int(11) DEFAULT NULL,
  PRIMARY KEY (`dt_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sdk_categorias`
--

LOCK TABLES `sdk_categorias` WRITE;
/*!40000 ALTER TABLE `sdk_categorias` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdk_categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sdk_compras`
--

DROP TABLE IF EXISTS `sdk_compras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sdk_compras` (
  `dt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dt_hash` varchar(30) CHARACTER SET latin1 NOT NULL,
  `dt_produto` bigint(20) NOT NULL,
  `dt_cadastrado` bigint(20) NOT NULL,
  `dt_data_compra` int(11) DEFAULT NULL,
  `dt_data_entrega` int(11) DEFAULT NULL,
  `dt_ativado` int(11) DEFAULT NULL,
  `dt_finalizado` int(11) DEFAULT NULL,
  `dt_pagamento` int(11) DEFAULT NULL,
  `dt_detalhes` text CHARACTER SET latin1,
  `dt_valor` decimal(10,2) DEFAULT NULL,
  `dt_quantidade` int(11) DEFAULT NULL,
  `dt_frete` decimal(10,2) DEFAULT NULL,
  `dt_cep` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `dt_endereco` varchar(200) DEFAULT NULL,
  `dt_arquivos` text,
  PRIMARY KEY (`dt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sdk_compras`
--

LOCK TABLES `sdk_compras` WRITE;
/*!40000 ALTER TABLE `sdk_compras` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdk_compras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sdk_contatos`
--

DROP TABLE IF EXISTS `sdk_contatos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sdk_contatos` (
  `dt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dt_hash` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `dt_tipo` int(11) NOT NULL,
  `dt_pai` int(11) DEFAULT NULL,
  `dt_nome` varchar(300) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `dt_email` varchar(300) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `dt_titulo` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `dt_mensagem` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `dt_data` bigint(20) NOT NULL,
  `dt_campo1` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `dt_campo2` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `dt_campo3` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`dt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sdk_contatos`
--

LOCK TABLES `sdk_contatos` WRITE;
/*!40000 ALTER TABLE `sdk_contatos` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdk_contatos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sdk_newsletters`
--

DROP TABLE IF EXISTS `sdk_newsletters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sdk_newsletters` (
  `dt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dt_hash` varchar(30) CHARACTER SET latin1 NOT NULL,
  `dt_categoria` int(11) DEFAULT NULL,
  `dt_titulo` varchar(300) CHARACTER SET latin1 NOT NULL,
  `dt_alias` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `dt_autor` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `dt_data_inicio` int(11) DEFAULT NULL,
  `dt_data_fim` int(11) DEFAULT NULL,
  `dt_ativado` int(11) DEFAULT NULL,
  `dt_destaque` int(11) DEFAULT NULL,
  `dt_criado_por` bigint(20) DEFAULT NULL,
  `dt_criado_data` int(11) DEFAULT NULL,
  `dt_alterado_data` int(11) DEFAULT NULL,
  `dt_corpo` text CHARACTER SET latin1,
  `dt_campo1` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_campo2` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_campo3` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_meta_descricao` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_meta_tags` text CHARACTER SET latin1,
  `dt_hits` int(11) DEFAULT NULL,
  `dt_enviados` int(11) DEFAULT NULL,
  `dt_relatorio` text CHARACTER SET latin1,
  PRIMARY KEY (`dt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sdk_newsletters`
--

LOCK TABLES `sdk_newsletters` WRITE;
/*!40000 ALTER TABLE `sdk_newsletters` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdk_newsletters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sdk_paginas`
--

DROP TABLE IF EXISTS `sdk_paginas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sdk_paginas` (
  `dt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dt_hash` varchar(30) CHARACTER SET latin1 NOT NULL,
  `dt_categoria` int(11) DEFAULT NULL,
  `dt_titulo` varchar(300) CHARACTER SET latin1 NOT NULL,
  `dt_alias` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `dt_autor` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `dt_data_inicio` int(11) DEFAULT NULL,
  `dt_data_fim` int(11) DEFAULT NULL,
  `dt_ativado` int(11) DEFAULT NULL,
  `dt_destaque` int(11) DEFAULT NULL,
  `dt_criado_por` bigint(20) DEFAULT NULL,
  `dt_criado_data` int(11) DEFAULT NULL,
  `dt_alterado_data` int(11) DEFAULT NULL,
  `dt_corpo` text CHARACTER SET latin1,
  `dt_campo1` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_campo2` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_campo3` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_meta_descricao` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_meta_tags` text CHARACTER SET latin1,
  `dt_hits` int(11) DEFAULT NULL,
  PRIMARY KEY (`dt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sdk_paginas`
--

LOCK TABLES `sdk_paginas` WRITE;
/*!40000 ALTER TABLE `sdk_paginas` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdk_paginas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sdk_posts`
--

DROP TABLE IF EXISTS `sdk_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sdk_posts` (
  `dt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dt_hash` varchar(30) CHARACTER SET latin1 NOT NULL,
  `dt_categoria` int(11) DEFAULT NULL,
  `dt_titulo` varchar(300) CHARACTER SET latin1 NOT NULL,
  `dt_alias` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `dt_autor` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `dt_data_inicio` int(11) DEFAULT NULL,
  `dt_data_fim` int(11) DEFAULT NULL,
  `dt_ativado` int(11) DEFAULT NULL,
  `dt_destaque` int(11) DEFAULT NULL,
  `dt_criado_por` bigint(20) DEFAULT NULL,
  `dt_criado_data` int(11) DEFAULT NULL,
  `dt_alterado_data` int(11) DEFAULT NULL,
  `dt_corpo` text CHARACTER SET latin1,
  `dt_galeria` text CHARACTER SET latin1,
  `dt_arquivos` text,
  `dt_codigo` text CHARACTER SET latin1,
  `dt_campo1` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_campo2` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_campo3` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_campo4` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_meta_descricao` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_meta_tags` text CHARACTER SET latin1,
  `dt_hits` int(11) DEFAULT NULL,
  PRIMARY KEY (`dt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sdk_posts`
--

LOCK TABLES `sdk_posts` WRITE;
/*!40000 ALTER TABLE `sdk_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdk_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sdk_produtos`
--

DROP TABLE IF EXISTS `sdk_produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sdk_produtos` (
  `dt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dt_hash` varchar(30) CHARACTER SET latin1 NOT NULL,
  `dt_categoria` int(11) DEFAULT NULL,
  `dt_titulo` varchar(300) CHARACTER SET latin1 NOT NULL,
  `dt_alias` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  `dt_autor` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `dt_data_inicio` int(11) DEFAULT NULL,
  `dt_data_fim` int(11) DEFAULT NULL,
  `dt_ativado` int(11) DEFAULT NULL,
  `dt_finalizado` int(11) DEFAULT NULL,
  `dt_criado_por` bigint(20) DEFAULT NULL,
  `dt_criado_data` int(11) DEFAULT NULL,
  `dt_alterado_data` int(11) DEFAULT NULL,
  `dt_detalhes` text CHARACTER SET latin1,
  `dt_galeria` text CHARACTER SET latin1,
  `dt_valor` decimal(10,2) DEFAULT NULL,
  `dt_quantidade` int(11) DEFAULT NULL,
  `dt_campo1` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_campo2` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_meta_descricao` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `dt_meta_tags` text CHARACTER SET latin1,
  `dt_hits` int(11) DEFAULT NULL,
  PRIMARY KEY (`dt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sdk_produtos`
--

LOCK TABLES `sdk_produtos` WRITE;
/*!40000 ALTER TABLE `sdk_produtos` DISABLE KEYS */;
/*!40000 ALTER TABLE `sdk_produtos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-12-11 15:27:38
