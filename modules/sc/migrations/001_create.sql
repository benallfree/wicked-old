CREATE TABLE IF NOT EXISTS `secure_callbacks` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(500) NOT NULL,
  `data` longtext NOT NULL,
  `should_expire` BOOLEAN NOT NULL DEFAULT  '0',
  `expires_at` DATE NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;