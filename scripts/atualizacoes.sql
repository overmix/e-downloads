drop table pedidos;
CREATE TABLE  `edownload`.`pedidos` (
  `id_pedidos` int(11) NOT NULL AUTO_INCREMENT,
  `id_produto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `pedido_em` timestamp NOT NULL,
  `liberado_em` date NOT NULL,
  `downloads` int(11) NOT NULL,
  PRIMARY KEY (`id_pedidos`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1