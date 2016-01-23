-- phpMyAdmin SQL Dump
-- version 4.2.12deb2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 11 Septembre 2015 à 22:26
-- Version du serveur :  5.5.44-0+deb8u1
-- Version de PHP :  5.6.12-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `DockerStation`
--

-- --------------------------------------------------------

--
-- Structure de la table `ds_agent`
--

CREATE TABLE IF NOT EXISTS `ds_agent` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `version` varchar(15) NOT NULL,
  `Comment` longtext NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ds_docker`
--

CREATE TABLE IF NOT EXISTS `ds_docker` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `os` int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ds_actions`
--

CREATE TABLE IF NOT EXISTS `ds_actions` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `command` varchar(1024) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ds_status`
--

CREATE TABLE IF NOT EXISTS `ds_status` (
`id` int(2) NOT NULL AUTO_INCREMENT,
  `status` varchar(32) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ds_instance`
--

CREATE TABLE IF NOT EXISTS `ds_instance` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `version` varchar(15) NOT NULL,
  `Comment` longtext NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ds_profile`
--

CREATE TABLE IF NOT EXISTS `ds_profile` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `comment` longtext NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ds_users`
--

CREATE TABLE IF NOT EXISTS `ds_users` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `Comment` longtext,
  PRIMARY KEY (id),
  INDEX profile_id (profile_id),
  UNIQUE KEY `login` (`login`),
  FOREIGN KEY (profile_id)
	REFERENCES ds_profile(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ds_hosts_client`
--

CREATE TABLE IF NOT EXISTS `ds_hosts_client` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `hostname` varchar(128) NOT NULL,
  `os` varchar(32) NOT NULL,
  `status_id` int(2) NOT NULL,
  `comment` longtext NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY `hostname` (`hostname`),
  INDEX status_id (status_id),
  FOREIGN KEY (status_id)
    REFERENCES ds_status(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='DocketStation list host client';

-- --------------------------------------------------------

--
-- Structure de la table `ds_hosts_agents`
--

CREATE TABLE IF NOT EXISTS `ds_hosts_agents` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `status_id` int(2) NOT NULL,
  PRIMARY KEY (id),
  INDEX host_id (host_id),
  INDEX agent_id (agent_id),
  INDEX status_id (status_id),
  FOREIGN KEY (agent_id)
	REFERENCES ds_agent(id),
  FOREIGN KEY (host_id)
	REFERENCES ds_hosts_client(id)
	ON DELETE CASCADE,
  FOREIGN KEY (status_id)
    REFERENCES ds_status(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ds_hosts_docker`
--

CREATE TABLE IF NOT EXISTS `ds_hosts_docker` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) NOT NULL,
  `docker_id` int(11) NOT NULL,
  `status_id` int(2) NOT NULL,
  `version` varchar(15) NOT NULL,
  PRIMARY KEY (id),
  INDEX docker_id (docker_id),
  INDEX host_id (host_id),
  INDEX status_id (status_id),
  FOREIGN KEY (docker_id)
	REFERENCES ds_docker(id),
  FOREIGN KEY (host_id)
	REFERENCES ds_hosts_client(id)
	ON DELETE CASCADE,
  FOREIGN KEY (status_id)
    REFERENCES ds_status(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ds_hosts_instances`
--

CREATE TABLE IF NOT EXISTS `ds_hosts_instances` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `status_id` int(2) NOT NULL,
  PRIMARY KEY (id),
  INDEX host_id (host_id),
  INDEX instance_id (instance_id),
  INDEX status_id (status_id),
  FOREIGN KEY (instance_id)
	REFERENCES ds_instance(id),
  FOREIGN KEY (host_id)
	REFERENCES ds_hosts_client(id)
	ON DELETE CASCADE,
  FOREIGN KEY (status_id)
    REFERENCES ds_status(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ds_tasks`
--

CREATE TABLE IF NOT EXISTS `ds_tasks` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `step_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `status_id` int(2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `log_file` varchar(128),
  PRIMARY KEY (id),
  INDEX status_id (status_id),
  INDEX user_id (user_id),
  INDEX host_id (host_id),
  INDEX job_id (job_id),
  INDEX action_id (action_id),
  FOREIGN KEY (user_id)
	REFERENCES ds_users(id),
  FOREIGN KEY (host_id)
	REFERENCES ds_hosts_client(id),
  FOREIGN KEY (action_id)
	REFERENCES ds_actions(id),
  FOREIGN KEY (status_id)
	REFERENCES ds_status(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
