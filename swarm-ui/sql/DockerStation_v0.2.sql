-- phpMyAdmin SQL Dump
-- version 4.2.12deb2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Sam 03 Octobre 2015 à 08:03
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
-- Structure de la table `ds_actions`
--

CREATE TABLE IF NOT EXISTS `ds_actions` (
`id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `command` varchar(1024) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ds_actions`
--

INSERT INTO `ds_actions` (`id`, `name`, `command`) VALUES
(1, 'add host', ''),
(2, 'Check ansible access', 'ansible ..'),
(3, 'Install Docker package', 'ansible ...'),
(4, 'Install DS Agent', 'ansible ...'),
(5, 'Update host information', ''),
(6, 'Pull Docker Image', 'Ansible action'),
(7, 'Create Container', 'Ansible'),
(8, 'Stop container', 'Ansible '),
(9, 'Start container', 'Ansible'),
(10, 'Stop agent', 'Ansible'),
(11, 'Start agent', 'Ansible');

-- --------------------------------------------------------

--
-- Structure de la table `ds_agent`
--

CREATE TABLE IF NOT EXISTS `ds_agent` (
`id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `version` varchar(15) NOT NULL,
  `active` int(1) NOT NULL,
  `Comment` longtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ds_agent`
--

INSERT INTO `ds_agent` (`id`, `name`, `version`, `active`, `Comment`) VALUES
(1, 'ds-agent', '0.0.1', 0, ''),
(2, 'ds-agent', '0.0.2', 1, '');

-- --------------------------------------------------------

--
-- Structure de la table `ds_hosts_agents`
--

CREATE TABLE IF NOT EXISTS `ds_hosts_agents` (
`id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `status_id` int(2) NOT NULL,
  `hearthbeat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `set_alarm` int(1) NOT NULL,
  `random` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ds_hosts_agents`
--

-- --------------------------------------------------------

--
-- Structure de la table `ds_hosts_client`
--

CREATE TABLE IF NOT EXISTS `ds_hosts_client` (
`id` int(11) NOT NULL,
  `hostname` varchar(128) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `os` varchar(32) NOT NULL,
  `status_id` int(2) NOT NULL,
  `hearthbeat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `comment` longtext NOT NULL,
  `set_alarm` int(1) NOT NULL,
  `random` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='DocketStation list host client';

--
-- Contenu de la table `ds_hosts_client`
--

INSERT INTO `ds_hosts_client` (`id`, `hostname`, `ip_address`, `os`, `status_id`, `hearthbeat`, `comment`, `set_alarm`, `random`) VALUES
(0, 'all', '0.0.0.0', 'all', 1, '2015-09-26 13:35:18', '', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `ds_hosts_docker`
--

CREATE TABLE IF NOT EXISTS `ds_hosts_docker` (
`id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `status_id` int(2) NOT NULL,
  `hearthbeat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `version` varchar(32) NOT NULL,
  `set_alarm` int(1) NOT NULL,
  `random` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ds_hosts_docker`
--

-- --------------------------------------------------------

--
-- Structure de la table `ds_hosts_instances`
--

CREATE TABLE IF NOT EXISTS `ds_hosts_instances` (
`id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `status_id` int(2) NOT NULL,
  `hearthbeat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `set_alarm` int(1) NOT NULL,
  `container_id` varchar(12) NOT NULL,
  `options` varchar(256) NOT NULL,
  `random` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ds_hosts_instances`
--

-- --------------------------------------------------------

--
-- Structure de la table `ds_instance`
--

CREATE TABLE IF NOT EXISTS `ds_instance` (
`id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `Description` longtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ds_instance`
--

-- --------------------------------------------------------

--
-- Structure de la table `ds_profile`
--

CREATE TABLE IF NOT EXISTS `ds_profile` (
`id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `comment` longtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ds_profile`
--

INSERT INTO `ds_profile` (`id`, `name`, `comment`) VALUES
(1, 'Admin', 'Admin account DockerStation'),
(2, 'Watcher', 'Watcher account. No action possible');

-- --------------------------------------------------------

--
-- Structure de la table `ds_status`
--

CREATE TABLE IF NOT EXISTS `ds_status` (
`id` int(2) NOT NULL,
  `status` varchar(32) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ds_status`
--

INSERT INTO `ds_status` (`id`, `status`) VALUES
(1, 'running'),
(2, 'stopped'),
(3, 'waiting'),
(4, 'offline'),
(5, 'unknown'),
(6, 'installing'),
(7, 'stopping'),
(8, 'starting'),
(9, 'success'),
(10, 'failed'),
(11, 'canceled');

-- --------------------------------------------------------

--
-- Structure de la table `ds_tasks`
--

CREATE TABLE IF NOT EXISTS `ds_tasks` (
`id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `job_id` varchar(32) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `options` varchar(256) NOT NULL,
  `step_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `status_id` int(2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `log_file` varchar(128) DEFAULT NULL,
  `progress` int(3) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ds_tasks`
--

-- --------------------------------------------------------

--
-- Structure de la table `ds_users`
--

CREATE TABLE IF NOT EXISTS `ds_users` (
`id` int(11) NOT NULL,
  `login` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `Comment` longtext
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ds_users`
--

INSERT INTO `ds_users` (`id`, `login`, `password`, `firstname`, `lastname`, `profile_id`, `Comment`) VALUES
(1, 'admin', '$2y$09$euamvcNtYHuEBbZaApjbgeYE4qeHvfpkmnnqiFqYdKnUWS6kRoGeG', 'admin', 'admin', 1, 'Default User Admin'),
(2, 'csrx8264', '$2y$10$5GMT082X9fraHcnhzR1I4.668xaUk6W92ivtFp11S8fS9qrRwVRoG', 'David', 'Lepoultier', 1, 'Admin account'),
(3, 'consult', '$2y$09$RohxGABaWzH3IaHIVzjAbeb3MDz2Ce2X.AaL9Pbvg9yGV4I5zsNTq', 'consult', 'consult', 2, 'Watcher account');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `ds_actions`
--
ALTER TABLE `ds_actions`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ds_agent`
--
ALTER TABLE `ds_agent`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ds_hosts_agents`
--
ALTER TABLE `ds_hosts_agents`
 ADD PRIMARY KEY (`id`), ADD KEY `host_id` (`host_id`), ADD KEY `agent_id` (`agent_id`), ADD KEY `status_id` (`status_id`);

--
-- Index pour la table `ds_hosts_client`
--
ALTER TABLE `ds_hosts_client`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `hostname` (`hostname`), ADD KEY `status_id` (`status_id`);

--
-- Index pour la table `ds_hosts_docker`
--
ALTER TABLE `ds_hosts_docker`
 ADD PRIMARY KEY (`id`), ADD KEY `host_id` (`host_id`), ADD KEY `status_id` (`status_id`);

--
-- Index pour la table `ds_hosts_instances`
--
ALTER TABLE `ds_hosts_instances`
 ADD PRIMARY KEY (`id`), ADD KEY `host_id` (`host_id`), ADD KEY `instance_id` (`instance_id`), ADD KEY `status_id` (`status_id`);

--
-- Index pour la table `ds_instance`
--
ALTER TABLE `ds_instance`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ds_profile`
--
ALTER TABLE `ds_profile`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ds_status`
--
ALTER TABLE `ds_status`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ds_tasks`
--
ALTER TABLE `ds_tasks`
 ADD PRIMARY KEY (`id`), ADD KEY `status_id` (`status_id`), ADD KEY `user_id` (`user_id`), ADD KEY `host_id` (`host_id`), ADD KEY `job_id` (`job_id`), ADD KEY `action_id` (`action_id`);

--
-- Index pour la table `ds_users`
--
ALTER TABLE `ds_users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `login` (`login`), ADD KEY `profile_id` (`profile_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `ds_actions`
--
ALTER TABLE `ds_actions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `ds_agent`
--
ALTER TABLE `ds_agent`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `ds_hosts_agents`
--
ALTER TABLE `ds_hosts_agents`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `ds_hosts_client`
--
ALTER TABLE `ds_hosts_client`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `ds_hosts_docker`
--
ALTER TABLE `ds_hosts_docker`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `ds_hosts_instances`
--
ALTER TABLE `ds_hosts_instances`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `ds_instance`
--
ALTER TABLE `ds_instance`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `ds_profile`
--
ALTER TABLE `ds_profile`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `ds_status`
--
ALTER TABLE `ds_status`
MODIFY `id` int(2) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `ds_tasks`
--
ALTER TABLE `ds_tasks`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT pour la table `ds_users`
--
ALTER TABLE `ds_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `ds_hosts_agents`
--
ALTER TABLE `ds_hosts_agents`
ADD CONSTRAINT `ds_hosts_agents_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `ds_agent` (`id`),
ADD CONSTRAINT `ds_hosts_agents_ibfk_2` FOREIGN KEY (`host_id`) REFERENCES `ds_hosts_client` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `ds_hosts_agents_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `ds_status` (`id`);

--
-- Contraintes pour la table `ds_hosts_client`
--
ALTER TABLE `ds_hosts_client`
ADD CONSTRAINT `ds_hosts_client_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `ds_status` (`id`);

--
-- Contraintes pour la table `ds_hosts_docker`
--
ALTER TABLE `ds_hosts_docker`
ADD CONSTRAINT `ds_hosts_docker_ibfk_2` FOREIGN KEY (`host_id`) REFERENCES `ds_hosts_client` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `ds_hosts_docker_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `ds_status` (`id`);

--
-- Contraintes pour la table `ds_hosts_instances`
--
ALTER TABLE `ds_hosts_instances`
ADD CONSTRAINT `ds_hosts_instances_ibfk_1` FOREIGN KEY (`instance_id`) REFERENCES `ds_instance` (`id`),
ADD CONSTRAINT `ds_hosts_instances_ibfk_2` FOREIGN KEY (`host_id`) REFERENCES `ds_hosts_client` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `ds_hosts_instances_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `ds_status` (`id`);

--
-- Contraintes pour la table `ds_tasks`
--
ALTER TABLE `ds_tasks`
ADD CONSTRAINT `ds_tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `ds_users` (`id`),
ADD CONSTRAINT `ds_tasks_ibfk_2` FOREIGN KEY (`host_id`) REFERENCES `ds_hosts_client` (`id`),
ADD CONSTRAINT `ds_tasks_ibfk_3` FOREIGN KEY (`action_id`) REFERENCES `ds_actions` (`id`),
ADD CONSTRAINT `ds_tasks_ibfk_4` FOREIGN KEY (`status_id`) REFERENCES `ds_status` (`id`);

--
-- Contraintes pour la table `ds_users`
--
ALTER TABLE `ds_users`
ADD CONSTRAINT `ds_users_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `ds_profile` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
