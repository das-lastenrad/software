

--
-- Tabellenstruktur für Tabelle `blogs`
--

CREATE TABLE IF NOT EXISTS `blogs` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `cdate` datetime DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `teaser` text,
  `content` text,
  `url` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `objects`
--

CREATE TABLE IF NOT EXISTS `objects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `openinghours`
--

CREATE TABLE IF NOT EXISTS `openinghours` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` datetime NOT NULL,
  `to` datetime NOT NULL,
  `station` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rentals`
--

CREATE TABLE IF NOT EXISTS `rentals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL,
  `object` int(10) unsigned NOT NULL,
  `from` datetime NOT NULL,
  `to` datetime NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `role` varchar(8) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `pass` varchar(60) DEFAULT NULL,
  `firstname` varchar(32) DEFAULT NULL,
  `lastname` varchar(32) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
