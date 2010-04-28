-- MySQL dump 10.13  Distrib 5.1.37, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: edownload
-- ------------------------------------------------------
-- Server version	5.1.37-1ubuntu5.1

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
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `id_produto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `pedido_em` datetime NOT NULL,
  `liberado_em` datetime DEFAULT NULL,
  `downloads` int(11) DEFAULT '0',
  `usar_ate` date DEFAULT NULL,
  `limite` int(11) NOT NULL DEFAULT '0',
  `status` enum('Ativo','Bloqueado') NOT NULL DEFAULT 'Bloqueado',
  `form_pgs` text NOT NULL,
  PRIMARY KEY (`id_pedido`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES (1,6,6,'2010-04-01 10:46:56','2010-04-01 11:39:58',0,'2010-04-02',10,'Ativo','<form target=\"pagseguro\" action=\"https://pagseguro.uol.com.br/security/webpagamentos/webpagto.aspx\" method=\"post\">\n  <input type=\"hidden\" name=\"email_cobranca\" value=\"elcio@visie.com.br\"  />\n  <input type=\"hidden\" name=\"ref_transacao\" value=\"1\"  />\n  <input type=\"hidden\" name=\"tipo\" value=\"CP\"  />\n  <input type=\"hidden\" name=\"moeda\" value=\"BRL\"  />\n  <input type=\"hidden\" name=\"item_id_1\" value=\"6\"  />\n  <input type=\"hidden\" name=\"item_descr_1\" value=\"Planeta água\"  />\n  <input type=\"hidden\" name=\"item_quant_1\" value=\"1\"  />\n  <input type=\"hidden\" name=\"item_valor_1\" value=\"235\"  />'),(2,16,6,'2010-04-05 16:30:04','2010-04-05 17:49:53',0,'0000-00-00',2,'Ativo','<form target=\"pagseguro\" action=\"https://pagseguro.uol.com.br/security/webpagamentos/webpagto.aspx\" method=\"post\">\n  <input type=\"hidden\" name=\"email_cobranca\" value=\"elcio@visie.com.br\"  />\n  <input type=\"hidden\" name=\"ref_transacao\" value=\"2\"  />\n  <input type=\"hidden\" name=\"tipo\" value=\"CP\"  />\n  <input type=\"hidden\" name=\"moeda\" value=\"BRL\"  />\n  <input type=\"hidden\" name=\"item_id_1\" value=\"16\"  />\n  <input type=\"hidden\" name=\"item_descr_1\" value=\"Bebendo água\"  />\n  <input type=\"hidden\" name=\"item_quant_1\" value=\"1\"  />\n  <input type=\"hidden\" name=\"item_valor_1\" value=\"214\"  />'),(3,15,6,'2010-04-05 16:37:47','2010-04-05 17:47:15',0,'2010-04-05',2,'Ativo','<form target=\"pagseguro\" action=\"https://pagseguro.uol.com.br/security/webpagamentos/webpagto.aspx\" method=\"post\">\n  <input type=\"hidden\" name=\"email_cobranca\" value=\"elcio@visie.com.br\"  />\n  <input type=\"hidden\" name=\"ref_transacao\" value=\"3\"  />\n  <input type=\"hidden\" name=\"tipo\" value=\"CP\"  />\n  <input type=\"hidden\" name=\"moeda\" value=\"BRL\"  />\n  <input type=\"hidden\" name=\"item_id_1\" value=\"15\"  />\n  <input type=\"hidden\" name=\"item_descr_1\" value=\"Planeta água\"  />\n  <input type=\"hidden\" name=\"item_quant_1\" value=\"1\"  />\n  <input type=\"hidden\" name=\"item_valor_1\" value=\"125\"  />');
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produtos` (
  `id_produto` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(128) NOT NULL,
  `arquivo` varchar(128) NOT NULL,
  `preco` float NOT NULL,
  `image` varchar(128) NOT NULL,
  `atualizado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `descricao` text NOT NULL,
  PRIMARY KEY (`id_produto`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos`
--

LOCK TABLES `produtos` WRITE;
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
INSERT INTO `produtos` VALUES (15,'Planeta água','arquivo.rar',1.25,'terra-e-agua.jpg','2010-04-05 18:17:11',1,'deleteArquivo'),(16,'Bebendo água','arquivo2.rar',2.14,'beber-agua-2.jpg','2010-04-05 18:18:50',0,'Testando...');
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `senha` varchar(128) NOT NULL,
  `telefone` varchar(128) NOT NULL,
  `cadastrado_em` datetime NOT NULL,
  `group` int(11) NOT NULL DEFAULT '0',
  `controle` varchar(255) NOT NULL,
  `status` enum('Ativo','Bloqueado') NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (2,'Luciano Dias Mota','ldmotta@visie.com.br','e10adc3949ba59abbe56e057f20f883e','(11)5563-2037','0000-00-00 00:00:00',1,'','Ativo'),(6,'Luciano D. Mota','ldmotta@gmail.com','e10adc3949ba59abbe56e057f20f883e','(11)5563-2037','0000-00-00 00:00:00',0,'','Ativo');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-04-07 17:00:28
