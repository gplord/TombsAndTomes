-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 07, 2017 at 03:56 PM
-- Server version: 5.6.33
-- PHP Version: 7.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `dungeon`
--

-- --------------------------------------------------------

--
-- Table structure for table `ability`
--

CREATE TABLE `ability` (
  `ability_id` int(3) NOT NULL,
  `ability_name` varchar(255) NOT NULL,
  `ability_type_id` int(3) NOT NULL,
  `element_id` int(2) NOT NULL DEFAULT '0',
  `ability_priority` int(2) NOT NULL DEFAULT '1',
  `ability_cost` int(3) NOT NULL DEFAULT '0',
  `ability_cooldown` int(2) DEFAULT '0',
  `ability_damage` int(3) DEFAULT NULL,
  `ability_levelreq` int(2) DEFAULT '0',
  `ability_desc` varchar(255) NOT NULL,
  `effect_id` int(5) DEFAULT NULL,
  `target_type_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ability`
--

INSERT INTO `ability` (`ability_id`, `ability_name`, `ability_type_id`, `element_id`, `ability_priority`, `ability_cost`, `ability_cooldown`, `ability_damage`, `ability_levelreq`, `ability_desc`, `effect_id`, `target_type_id`) VALUES
(1, 'Revolver / Knife', 1, 0, 1, 2, 0, 2, 0, 'Van Helsing\'s trusted weapons, able to fight both people and monsters, near and far.', NULL, 0),
(3, 'Lockpick', 4, 0, 1, 1, 0, 0, 0, 'Tools for breaking through physical locks of up to moderate intricacy.', NULL, 0),
(4, 'Sacred Weapons', 9, 3, 1, 1, 0, 3, 0, 'A blessed wooden stake, holy water, garlic, and other weapons used in the fighting of demons and other unholy monsters.', NULL, 0),
(5, 'Claws and Fangs', 1, 0, 1, 0, 0, 2, 0, 'Dracula\'s vicious flurry of vampiric claws and fangs.', NULL, 4),
(6, 'Blood Drink', 2, 4, 2, 5, 2, 2, 0, 'Dracula leaps upon his target with inhuman speed, drinking their blood to replenish his own health.', 1, 4),
(7, 'Bat Bite', 1, 0, 3, 4, 3, 3, 0, 'Dracula assumes the form of a giant bat, swooping upon his prey with a ferocious bite.', NULL, 4),
(8, 'Brawling/Pistol', 1, 0, 1, 2, 0, 2, 0, 'Sherlock attacks from either close- or long-range, dealing moderate damage.', NULL, 4),
(9, 'Deduction', 7, 0, 1, 2, 0, 1, 0, 'Sherlock assesses the villain or object before him, revealing its hidden properties.', NULL, 7),
(10, 'Interrogation', 8, 0, 1, 2, 0, 1, 0, 'Sherlock enters into a game of wits and words with an enemy, expertly revealing information they may be hiding.', NULL, 4),
(11, 'Sword and Shield', 1, 0, 1, 2, 0, 3, 0, 'Beowulf attacks the villain with his sword and shield, dealing physical damage and protecting himself from incoming damage on the villain\'s next turn.', NULL, 4),
(12, 'Legendary Strength', 1, 0, 1, 3, 0, 4, 0, 'Beowulf lunges, grappling with his brute strength, dealing additional physical damage but leaving himself vulnerable to additional damage on the villain\'s next attack.', NULL, 4),
(13, 'Commanding Presence', 5, 0, 1, 3, 1, 1, 0, 'Beowulf does not attack this turn, but instead commands the battle, inspiring his allies to both deal more damage and receive less damage for the next two turns.', NULL, 8),
(14, 'Incendio (Flame)', 2, 1, 1, 2, 0, 2, 0, 'Hermione sends a blast of fire from her wand, burning a single enemy.', NULL, 4),
(15, 'Vulnera Sanentur (Mend Wounds)', 0, 0, 1, 2, 0, -3, 0, 'Hermione heals a single ally, or herself, from light damage.', NULL, 2),
(16, 'Brew Potion', 0, 0, 1, 2, 0, -3, 0, 'Hermione brews a random potion, which is added to the party\'s inventory.', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `effect`
--

CREATE TABLE `effect` (
  `effect_id` int(5) NOT NULL,
  `effect_name` varchar(255) NOT NULL,
  `effect_desc` varchar(255) DEFAULT NULL,
  `effect_level` int(2) UNSIGNED DEFAULT NULL,
  `effect_hp` int(4) UNSIGNED DEFAULT NULL,
  `effect_hptotal` int(4) UNSIGNED DEFAULT NULL,
  `effect_energy` int(4) UNSIGNED DEFAULT NULL,
  `effect_energytotal` int(4) UNSIGNED DEFAULT NULL,
  `effect_str` int(4) UNSIGNED DEFAULT NULL,
  `effect_dex` int(4) UNSIGNED DEFAULT NULL,
  `effect_int` int(4) UNSIGNED DEFAULT NULL,
  `effect_cng` int(4) UNSIGNED DEFAULT NULL,
  `effect_duration` int(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `effect`
--

INSERT INTO `effect` (`effect_id`, `effect_name`, `effect_desc`, `effect_level`, `effect_hp`, `effect_hptotal`, `effect_energy`, `effect_energytotal`, `effect_str`, `effect_dex`, `effect_int`, `effect_cng`, `effect_duration`) VALUES
(1, 'Vampiric Vitality', 'Stealing the blood of the living to replenish the undead.', 0, 2, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `element`
--

CREATE TABLE `element` (
  `element_id` int(2) NOT NULL,
  `element_name` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `element`
--

INSERT INTO `element` (`element_id`, `element_name`) VALUES
(0, 'Physical'),
(1, 'Fire'),
(2, 'Ice'),
(3, 'Sacred'),
(4, 'Evil'),
(5, 'Arcane');

-- --------------------------------------------------------

--
-- Table structure for table `hero`
--

CREATE TABLE `hero` (
  `hero_id` int(4) NOT NULL,
  `hero_name` varchar(255) NOT NULL,
  `hero_level` int(2) NOT NULL,
  `hero_hp` int(3) NOT NULL,
  `hero_energy` int(3) NOT NULL,
  `hero_str` int(2) NOT NULL,
  `hero_dex` int(2) NOT NULL,
  `hero_int` int(2) NOT NULL,
  `hero_cng` int(2) NOT NULL,
  `hero_desc` varchar(255) NOT NULL,
  `hero_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hero`
--

INSERT INTO `hero` (`hero_id`, `hero_name`, `hero_level`, `hero_hp`, `hero_energy`, `hero_str`, `hero_dex`, `hero_int`, `hero_cng`, `hero_desc`, `hero_image`) VALUES
(1, 'Abraham Van Helsing', 1, 10, 5, 3, 3, 5, 5, 'Van Helsing is an aged Dutch doctor with a wide range of interests and accomplishments. A vampire hunter and the archenemy of Count Dracula.', 'hero-abrahamvanhelsing.jpg'),
(2, 'Sherlock Holmes', 1, 10, 5, 3, 2, 6, 5, 'A "consulting detective" known for his proficiency with observation, forensic science, and logical reasoning, which he employs when investigating cases.', 'hero-sherlockholmes.jpg'),
(3, 'Hermione Granger', 1, 8, 7, 1, 3, 6, 6, 'Young student of magic at Hogwarts School of Witchcraft and Wizardry, who often uses her quick wit, deft recall, and encyclopaedic knowledge to help her friends.', 'hero-hermionegranger.jpg'),
(4, 'Beowulf', 1, 12, 4, 6, 4, 3, 3, 'Hero of the Geats, famed for his strength and courage, and renowned for his battles with Grendel and Grendel\'s mother in defense of Danish King Hrothgar.', 'hero-beowulf.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `hero_ability`
--

CREATE TABLE `hero_ability` (
  `hero_id` int(4) NOT NULL,
  `ability_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hero_ability`
--

INSERT INTO `hero_ability` (`hero_id`, `ability_id`) VALUES
(1, 1),
(1, 3),
(1, 4),
(2, 8),
(2, 9),
(2, 10),
(3, 14),
(3, 15),
(3, 16),
(4, 11),
(4, 12),
(4, 13);

-- --------------------------------------------------------

--
-- Table structure for table `hero_instance`
--

CREATE TABLE `hero_instance` (
  `hinst_id` varchar(9) NOT NULL,
  `hero_id` int(4) NOT NULL,
  `hinst_level` int(2) NOT NULL,
  `hinst_xp` int(11) NOT NULL,
  `hinst_xpnext` int(11) NOT NULL,
  `hinst_hp` int(3) NOT NULL,
  `hinst_hp_max` int(3) NOT NULL,
  `hinst_energy` int(3) NOT NULL,
  `hinst_energy_max` int(3) NOT NULL,
  `hinst_str` int(2) NOT NULL,
  `hinst_dex` int(2) NOT NULL,
  `hinst_int` int(2) NOT NULL,
  `hinst_cng` int(2) NOT NULL,
  `hinst_injured` tinyint(1) NOT NULL DEFAULT '0',
  `hinst_dead` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hero_instance`
--

INSERT INTO `hero_instance` (`hinst_id`, `hero_id`, `hinst_level`, `hinst_xp`, `hinst_xpnext`, `hinst_hp`, `hinst_hp_max`, `hinst_energy`, `hinst_energy_max`, `hinst_str`, `hinst_dex`, `hinst_int`, `hinst_cng`, `hinst_injured`, `hinst_dead`) VALUES
('c01010101', 1, 1, 0, 100, 10, 10, 5, 5, 3, 3, 5, 5, 0, 0),
('c02020202', 2, 1, 0, 100, 10, 10, 5, 5, 3, 2, 6, 5, 0, 0),
('c03030303', 3, 1, 0, 100, 8, 8, 7, 7, 1, 3, 6, 6, 0, 0),
('c04040404', 4, 1, 0, 100, 12, 12, 4, 4, 6, 4, 3, 3, 0, 0),
('c77777777', 3, 1, 0, 100, 8, 8, 7, 7, 1, 3, 6, 6, 0, 0),
('c88888888', 2, 1, 0, 100, 10, 10, 5, 5, 3, 2, 6, 5, 0, 0),
('c99999999', 1, 1, 0, 100, 10, 10, 5, 5, 3, 3, 5, 5, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `hero_instance_effect`
--

CREATE TABLE `hero_instance_effect` (
  `hinst_id` varchar(9) NOT NULL,
  `effect_id` int(5) NOT NULL,
  `effect_durationleft` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hero_instance_effect`
--

INSERT INTO `hero_instance_effect` (`hinst_id`, `effect_id`, `effect_durationleft`) VALUES
('c01010101', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `hero_instance_item`
--

CREATE TABLE `hero_instance_item` (
  `hinst_id` varchar(9) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_cooldown` int(2) NOT NULL,
  `item_count` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hint`
--

CREATE TABLE `hint` (
  `hint_id` int(5) NOT NULL,
  `task_id` int(4) NOT NULL,
  `hint_order` int(2) NOT NULL,
  `hint_penalty` int(5) NOT NULL DEFAULT '0',
  `hint_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hint`
--

INSERT INTO `hint` (`hint_id`, `task_id`, `hint_order`, `hint_penalty`, `hint_text`) VALUES
(1, 1, 1, 0, 'Type in the least secure password you can think of.'),
(2, 1, 2, 10, 'It starts with "pass"...'),
(3, 1, 3, 25, '...and ends with "word"'),
(4, 2, 1, 0, 'Type in the cutest small animal you can think of.'),
(5, 2, 2, 25, 'They are small doggos...'),
(6, 2, 3, 50, '...and they are puppies.'),
(7, 3, 1, 0, 'Type in the other cutest small animal you can think of.'),
(8, 3, 2, 50, 'They are small cats...'),
(9, 3, 3, 100, '...and they are kitties.');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `target_type_id` int(3) NOT NULL DEFAULT '0',
  `item_name` varchar(255) NOT NULL,
  `item_desc` varchar(255) NOT NULL,
  `item_cost` int(7) DEFAULT NULL,
  `item_damage` int(3) DEFAULT NULL,
  `item_ability_type` int(3) DEFAULT NULL,
  `item_cooldown` int(2) NOT NULL DEFAULT '0',
  `item_singleuse` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE `player` (
  `player_id` varchar(9) NOT NULL,
  `player_name` varchar(255) NOT NULL,
  `player_firstname` varchar(24) NOT NULL,
  `player_email` varchar(255) NOT NULL,
  `player_password` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`player_id`, `player_name`, `player_firstname`, `player_email`, `player_password`) VALUES
('p11111111', 'Test Player', 'Test', 'test@example.com', 'password'),
('p22222222', 'Test2 Player', 'Test2', 'test2@example.com', 'password'),
('p33333333', 'Test3 Player', 'Test3', 'test3@example.com', 'password'),
('p44444444', 'Test4 Player', 'Test4', 'test4@example.com', 'password'),
('p77777777', 'Test5 Player', 'Test5', 'test5@example.com', 'password'),
('p88888888', 'Test6 Player', 'Test6', 'test6@example.com', 'password'),
('p99999999', 'Test7 Player', 'Test7', 'test7@example.com', 'password');

-- --------------------------------------------------------

--
-- Table structure for table `player_hero_instance`
--

CREATE TABLE `player_hero_instance` (
  `player_id` varchar(9) NOT NULL,
  `hinst_id` varchar(9) NOT NULL,
  `session_id` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `player_hero_instance`
--

INSERT INTO `player_hero_instance` (`player_id`, `hinst_id`, `session_id`) VALUES
('p11111111', 'c01010101', 's12345678'),
('p22222222', 'c02020202', 's12345678'),
('p33333333', 'c03030303', 's12345678'),
('p44444444', 'c04040404', 's12345678'),
('p77777777', 'c77777777', 's22222222'),
('p88888888', 'c88888888', 's22222222'),
('p99999999', 'c99999999', 's22222222');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `session_id` varchar(9) NOT NULL,
  `session_name` varchar(255) NOT NULL,
  `session_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `session_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `session_password` varchar(255) NOT NULL,
  `session_locked` tinyint(1) NOT NULL DEFAULT '0',
  `session_desc` text,
  `session_log` mediumtext,
  `session_reward` int(6) DEFAULT NULL,
  `session_complete` tinyint(1) NOT NULL DEFAULT '0',
  `session_player_count` int(2) NOT NULL,
  `session_update` int(10) NOT NULL,
  `session_ready` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`session_id`, `session_name`, `session_created`, `session_time`, `session_password`, `session_locked`, `session_desc`, `session_log`, `session_reward`, `session_complete`, `session_player_count`, `session_update`, `session_ready`) VALUES
('s12345678', 'Example Session', '2017-10-07 13:55:09', '2017-09-12 09:39:16', 'test', 0, 'This is a fake session to test the game.', '', 0, 0, 3, 1, 0),
('s22222222', 'Fake Second Session', '2017-10-05 07:06:43', '2017-09-19 04:57:19', 'password', 0, 'This one exists just to make sure we\'re selecting the right one.', '(Blank.)', NULL, 0, 3, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `session_item`
--

CREATE TABLE `session_item` (
  `session_id` varchar(9) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_cooldown` int(2) NOT NULL,
  `item_count` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `session_player`
--

CREATE TABLE `session_player` (
  `session_id` varchar(9) NOT NULL,
  `player_id` varchar(9) NOT NULL,
  `player_order` int(2) NOT NULL,
  `player_current` tinyint(1) NOT NULL DEFAULT '0',
  `player_ready` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `session_player`
--

INSERT INTO `session_player` (`session_id`, `player_id`, `player_order`, `player_current`, `player_ready`) VALUES
('s12345678', 'p11111111', 1, 1, 0),
('s12345678', 'p22222222', 2, 0, 1),
('s12345678', 'p33333333', 3, 0, 1),
('s22222222', 'p77777777', 3, 0, 0),
('s22222222', 'p88888888', 2, 0, 0),
('s22222222', 'p99999999', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `session_task`
--

CREATE TABLE `session_task` (
  `session_id` varchar(9) NOT NULL,
  `task_id` int(4) NOT NULL,
  `task_order` int(2) NOT NULL,
  `task_required` tinyint(1) NOT NULL DEFAULT '1',
  `task_hint_count` int(2) NOT NULL DEFAULT '0',
  `task_reward` int(6) NOT NULL,
  `task_complete` tinyint(1) NOT NULL DEFAULT '0',
  `task_active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `session_task`
--

INSERT INTO `session_task` (`session_id`, `task_id`, `task_order`, `task_required`, `task_hint_count`, `task_reward`, `task_complete`, `task_active`) VALUES
('s12345678', 1, 1, 1, 0, 0, 0, 0),
('s12345678', 2, 2, 1, 0, 0, 0, 0),
('s12345678', 3, 3, 1, 0, 0, 0, 0),
('s12345678', 4, 4, 1, 0, 0, 0, 0),
('s22222222', 1, 1, 1, 0, 0, 0, 0),
('s22222222', 2, 2, 1, 0, 0, 0, 0),
('s22222222', 3, 3, 1, 0, 0, 0, 0),
('s22222222', 4, 4, 1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `session_villain`
--

CREATE TABLE `session_villain` (
  `session_id` varchar(9) NOT NULL,
  `vinst_id` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `session_villain`
--

INSERT INTO `session_villain` (`session_id`, `vinst_id`) VALUES
('s12345678', 'i10101010'),
('s12345678', 'i20202020'),
('s12345678', 'i30303030'),
('s22222222', 'i10101010'),
('s22222222', 'i20202020'),
('s22222222', 'i30303030');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `task_id` int(4) NOT NULL,
  `task_type` tinyint(1) NOT NULL DEFAULT '1',
  `task_name` varchar(255) NOT NULL,
  `task_location` varchar(255) NOT NULL,
  `task_clue` text NOT NULL,
  `task_reward` int(6) NOT NULL,
  `task_desc` text NOT NULL,
  `task_debrief` text NOT NULL,
  `task_password` varchar(255) DEFAULT NULL,
  `villain_id` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`task_id`, `task_type`, `task_name`, `task_location`, `task_clue`, `task_reward`, `task_desc`, `task_debrief`, `task_password`, `villain_id`) VALUES
(1, 2, 'Type "Password"', 'The Internets', '', 100, 'This task asks you to type the word "Password" into the password field, as if you just searched high and low for it.\r\n\r\nBy the way, these description fields can get pretty long so it\'s probably good that this example text has a little length to it, for UI testing.', 'Congratulations!  You did the thing.', 'Password', 0),
(2, 3, 'Type "Puppies"', 'Information Superhighway', '', 250, 'This task asks you to type the word "Puppies" into the password field, as if you just searched high and low for it.\r\n\r\nBy the way, these description fields can get pretty long so it\'s probably good that this example text has a little length to it, for UI testing.', 'Congratulations!  You did the thing.', 'Puppies', 0),
(3, 3, 'Type "Kitties"', 'The Series of Tubes', '', 500, 'This task asks you to type the word "Kitties" into the password field, as if you just searched high and low for it.\r\n\r\nBy the way, these description fields can get pretty long so it\'s probably good that this example text has a little length to it, for UI testing.', 'Congratulations!  You did the thing.', 'Kitties', 0),
(4, 1, 'Defeat Dracula', 'Dracula\'s Lair', 'Dracula is most certainly an Evil character.  And he sure does seem to hate garlic.', 1000, 'Use your abilities and your special knowledge you\'ve acquired on your journey to defeat the evil Count Dracula!', 'Congratulations!  You\'ve defeated Count Dracula!  The world will certainly sleep easier tonight, knowing their blood is now a lot less likely to be consumed by an ancient monster.', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `type_ability`
--

CREATE TABLE `type_ability` (
  `ability_type_id` int(3) NOT NULL,
  `ability_type_name` varchar(255) NOT NULL,
  `ability_type_strongvs` int(3) NOT NULL,
  `ability_type_weakvs` int(3) NOT NULL,
  `ability_type_immunevs` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `type_ability`
--

INSERT INTO `type_ability` (`ability_type_id`, `ability_type_name`, `ability_type_strongvs`, `ability_type_weakvs`, `ability_type_immunevs`) VALUES
(1, 'Combat', 2, 1, 0),
(2, 'Magic', 1, 1, 0),
(3, 'Defense', 1, 10, 0),
(4, 'Mobility', 7, 0, 0),
(5, 'Command', 0, 0, 0),
(6, 'Reasoning', 8, 8, 0),
(7, 'Perception', 8, 4, 0),
(8, 'Negotiation', 6, 6, 0),
(9, 'Spiritual', 9, 9, 0),
(10, 'Stealth', 3, 7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `type_target`
--

CREATE TABLE `type_target` (
  `target_type_id` int(3) NOT NULL,
  `target_type_name` varchar(128) NOT NULL,
  `target_type_self` tinyint(1) NOT NULL DEFAULT '0',
  `target_type_ally` tinyint(1) NOT NULL DEFAULT '0',
  `target_type_enemy` tinyint(1) NOT NULL DEFAULT '0',
  `target_type_object` tinyint(1) NOT NULL DEFAULT '0',
  `target_type_all_heroes` tinyint(1) NOT NULL DEFAULT '0',
  `target_type_all_enemies` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `type_target`
--

INSERT INTO `type_target` (`target_type_id`, `target_type_name`, `target_type_self`, `target_type_ally`, `target_type_enemy`, `target_type_object`, `target_type_all_heroes`, `target_type_all_enemies`) VALUES
(1, 'Self', 1, 0, 0, 0, 0, 0),
(2, 'Any Hero', 1, 1, 0, 0, 0, 0),
(3, 'Allies', 0, 1, 0, 0, 0, 0),
(4, 'Enemies', 0, 0, 1, 0, 0, 0),
(5, 'Any Character', 1, 1, 1, 0, 0, 0),
(6, 'Objects', 0, 0, 0, 1, 0, 0),
(7, 'Enemies and Objects', 0, 0, 1, 1, 0, 0),
(8, 'All Allies', 1, 0, 0, 0, 1, 0),
(9, 'All Enemies', 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `type_task`
--

CREATE TABLE `type_task` (
  `task_type_id` int(2) NOT NULL,
  `task_type_name` varchar(255) NOT NULL,
  `task_type_desc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `type_task`
--

INSERT INTO `type_task` (`task_type_id`, `task_type_name`, `task_type_desc`) VALUES
(1, 'Combat', 'Battle and defeat one of the Villains of literary legend, gathering their treasures and the experience of fighting them.'),
(2, 'Exploration', 'Explore the library dungeon, seeking clues and locations that will shape the adventure ahead.'),
(3, 'Puzzle', 'Outsmart a puzzle or riddle that blocks your progress.'),
(4, 'Interaction', 'Negotiate, interrogate, threaten, or barter peace with one of the villains or other dungeon denizens.');

-- --------------------------------------------------------

--
-- Table structure for table `villain`
--

CREATE TABLE `villain` (
  `villain_id` int(4) NOT NULL,
  `villain_name` varchar(255) NOT NULL,
  `villain_level` int(2) NOT NULL,
  `villain_hp` int(3) NOT NULL,
  `villain_energy` int(3) NOT NULL,
  `villain_str` int(2) NOT NULL,
  `villain_dex` int(2) NOT NULL,
  `villain_int` int(2) NOT NULL,
  `villain_cng` int(2) NOT NULL,
  `villain_desc` varchar(255) NOT NULL,
  `villain_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `villain`
--

INSERT INTO `villain` (`villain_id`, `villain_name`, `villain_level`, `villain_hp`, `villain_energy`, `villain_str`, `villain_dex`, `villain_int`, `villain_cng`, `villain_desc`, `villain_image`) VALUES
(1, 'Count Dracula', 3, 30, 15, 3, 2, 6, 5, '', 'villain-countdracula.jpg'),
(2, 'The Minotaur', 1, 20, 10, 5, 3, 2, 2, '', ''),
(3, 'Grendel', 2, 25, 15, 5, 5, 2, 3, '', 'villain-grendel.jpg'),
(4, 'Frankenstein\'s Monster', 2, 25, 15, 7, 2, 4, 3, '', ''),
(5, 'Cthulhu', 4, 40, 25, 6, 4, 6, 4, '', ''),
(6, 'Medusa', 3, 25, 20, 5, 3, 5, 4, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `villain_ability`
--

CREATE TABLE `villain_ability` (
  `villain_id` int(4) NOT NULL,
  `ability_id` int(3) NOT NULL,
  `dialogue` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `villain_ability`
--

INSERT INTO `villain_ability` (`villain_id`, `ability_id`, `dialogue`) VALUES
(1, 5, 'Enough talk!  Have at you!'),
(1, 6, 'I vant... to drink... your blood!'),
(1, 7, 'I am the terror that flaps in the night!');

-- --------------------------------------------------------

--
-- Table structure for table `villain_element`
--

CREATE TABLE `villain_element` (
  `villain_id` varchar(9) NOT NULL,
  `element_id` int(2) NOT NULL,
  `element_resist` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `villain_element`
--

INSERT INTO `villain_element` (`villain_id`, `element_id`, `element_resist`) VALUES
('1', 1, 1.25),
('1', 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `villain_instance`
--

CREATE TABLE `villain_instance` (
  `vinst_id` varchar(9) NOT NULL,
  `villain_id` int(4) NOT NULL,
  `vinst_level` int(2) NOT NULL,
  `vinst_hp` int(3) NOT NULL,
  `vinst_hp_max` int(3) NOT NULL,
  `vinst_energy` int(3) NOT NULL,
  `vinst_energy_max` int(11) NOT NULL,
  `vinst_str` int(2) NOT NULL,
  `vinst_dex` int(11) NOT NULL,
  `vinst_int` int(2) NOT NULL,
  `vinst_cng` int(2) NOT NULL,
  `vinst_active` tinyint(1) NOT NULL DEFAULT '0',
  `vinst_defeated` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `villain_instance`
--

INSERT INTO `villain_instance` (`vinst_id`, `villain_id`, `vinst_level`, `vinst_hp`, `vinst_hp_max`, `vinst_energy`, `vinst_energy_max`, `vinst_str`, `vinst_dex`, `vinst_int`, `vinst_cng`, `vinst_active`, `vinst_defeated`) VALUES
('i10101010', 1, 3, 30, 30, 15, 15, 3, 2, 6, 5, 1, 0),
('i20202020', 2, 1, 20, 20, 10, 10, 5, 3, 2, 2, 0, 0),
('i30303030', 3, 2, 25, 25, 15, 15, 5, 5, 2, 3, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `villain_instance_ability`
--

CREATE TABLE `villain_instance_ability` (
  `vinst_id` varchar(9) NOT NULL,
  `ability_id` int(3) NOT NULL,
  `cooldown_left` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `villain_instance_ability`
--

INSERT INTO `villain_instance_ability` (`vinst_id`, `ability_id`, `cooldown_left`) VALUES
('i10101010', 5, 0),
('i10101010', 6, 0),
('i10101010', 7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `villain_instance_effect`
--

CREATE TABLE `villain_instance_effect` (
  `vinst_id` varchar(9) NOT NULL,
  `effect_id` int(5) NOT NULL,
  `effect_durationleft` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `villain_instance_effect`
--

INSERT INTO `villain_instance_effect` (`vinst_id`, `effect_id`, `effect_durationleft`) VALUES
('i10101010', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ability`
--
ALTER TABLE `ability`
  ADD PRIMARY KEY (`ability_id`),
  ADD UNIQUE KEY `ability_id` (`ability_id`);

--
-- Indexes for table `effect`
--
ALTER TABLE `effect`
  ADD PRIMARY KEY (`effect_id`);

--
-- Indexes for table `element`
--
ALTER TABLE `element`
  ADD UNIQUE KEY `element_id` (`element_id`);

--
-- Indexes for table `hero`
--
ALTER TABLE `hero`
  ADD PRIMARY KEY (`hero_id`),
  ADD UNIQUE KEY `hero_id` (`hero_id`),
  ADD UNIQUE KEY `hero_id_2` (`hero_id`);

--
-- Indexes for table `hero_ability`
--
ALTER TABLE `hero_ability`
  ADD UNIQUE KEY `hero_id` (`hero_id`,`ability_id`);

--
-- Indexes for table `hero_instance`
--
ALTER TABLE `hero_instance`
  ADD PRIMARY KEY (`hinst_id`),
  ADD UNIQUE KEY `character_id` (`hinst_id`);

--
-- Indexes for table `hint`
--
ALTER TABLE `hint`
  ADD PRIMARY KEY (`hint_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `player`
--
ALTER TABLE `player`
  ADD UNIQUE KEY `player_id` (`player_id`),
  ADD UNIQUE KEY `player_email` (`player_email`);

--
-- Indexes for table `player_hero_instance`
--
ALTER TABLE `player_hero_instance`
  ADD UNIQUE KEY `player_id` (`player_id`,`hinst_id`,`session_id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`session_id`),
  ADD UNIQUE KEY `session_id` (`session_id`);

--
-- Indexes for table `session_player`
--
ALTER TABLE `session_player`
  ADD UNIQUE KEY `session_id` (`session_id`,`player_id`);

--
-- Indexes for table `session_task`
--
ALTER TABLE `session_task`
  ADD UNIQUE KEY `session_id` (`session_id`,`task_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `type_ability`
--
ALTER TABLE `type_ability`
  ADD PRIMARY KEY (`ability_type_id`);

--
-- Indexes for table `type_target`
--
ALTER TABLE `type_target`
  ADD PRIMARY KEY (`target_type_id`);

--
-- Indexes for table `type_task`
--
ALTER TABLE `type_task`
  ADD PRIMARY KEY (`task_type_id`);

--
-- Indexes for table `villain`
--
ALTER TABLE `villain`
  ADD PRIMARY KEY (`villain_id`),
  ADD UNIQUE KEY `villain_id` (`villain_id`);

--
-- Indexes for table `villain_ability`
--
ALTER TABLE `villain_ability`
  ADD UNIQUE KEY `villain_id` (`villain_id`,`ability_id`);

--
-- Indexes for table `villain_element`
--
ALTER TABLE `villain_element`
  ADD UNIQUE KEY `villain_id` (`villain_id`,`element_id`);

--
-- Indexes for table `villain_instance`
--
ALTER TABLE `villain_instance`
  ADD PRIMARY KEY (`vinst_id`),
  ADD UNIQUE KEY `vinst_id` (`vinst_id`);

--
-- Indexes for table `villain_instance_ability`
--
ALTER TABLE `villain_instance_ability`
  ADD UNIQUE KEY `vinst_id` (`vinst_id`,`ability_id`);

--
-- Indexes for table `villain_instance_effect`
--
ALTER TABLE `villain_instance_effect`
  ADD UNIQUE KEY `vinst_id` (`vinst_id`,`effect_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ability`
--
ALTER TABLE `ability`
  MODIFY `ability_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `effect`
--
ALTER TABLE `effect`
  MODIFY `effect_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `element`
--
ALTER TABLE `element`
  MODIFY `element_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `hero`
--
ALTER TABLE `hero`
  MODIFY `hero_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `task_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `type_ability`
--
ALTER TABLE `type_ability`
  MODIFY `ability_type_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `type_target`
--
ALTER TABLE `type_target`
  MODIFY `target_type_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `type_task`
--
ALTER TABLE `type_task`
  MODIFY `task_type_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `villain`
--
ALTER TABLE `villain`
  MODIFY `villain_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;