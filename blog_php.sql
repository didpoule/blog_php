-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 15 Juin 2018 à 12:01
-- Version du serveur :  5.7.22-0ubuntu0.16.04.1
-- Version de PHP :  7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(4, 'about'),
(1, 'chapter'),
(2, 'edito'),
(3, 'synopsis');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `added` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `published` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `postId` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `comment`
--

INSERT INTO `comment` (`id`, `author`, `added`, `updated`, `published`, `content`, `postId`) VALUES
(50, 'Jojo', '2018-06-13 05:29:47', '2018-06-13 06:29:47', 1, 'Un com', 41),
(51, 'didpoule', '2018-06-13 06:29:58', '2018-06-13 06:29:58', 1, 'test 2', 41),
(52, 'fdsn fnds', '2018-06-13 06:30:04', '2018-06-13 06:30:04', 1, 'nsnw', 41),
(53, 'ewrwrewr', '2018-06-13 06:30:09', '2018-06-13 06:30:09', 1, 'ewrewrw', 41),
(54, ';iluliu', '2018-06-13 06:30:12', '2018-06-13 06:30:12', 1, 'liuliu', 41),
(55, 'hfghf', '2018-06-13 06:30:18', '2018-06-13 06:30:18', 1, 'hfhfhf', 41),
(58, 'qasadasdsad', '2018-06-14 07:28:04', '2018-06-14 07:28:04', 1, 'asdasdadad', 41),
(61, 'bfdsbs', '2018-06-14 07:38:38', '2018-06-14 07:38:38', 0, 'bfsbfs', 41),
(62, 'brewrbw', '2018-06-14 07:52:40', '2018-06-14 07:52:40', 0, 'brwbrwbrw', 41);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `added` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `published` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `category` int(10) UNSIGNED NOT NULL,
  `number` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `post`
--

INSERT INTO `post` (`id`, `title`, `added`, `updated`, `published`, `content`, `category`, `number`) VALUES
(28, 'Edito', '2018-06-14 12:07:28', '2018-06-14 12:07:28', 0, '<p class="p1" style="font-size: 16px; background-color: rgb(248, 249, 250);">Bienvenue sur ce blog consacrÃ© Ã  la publication de mon nouveau roman intitulÃ©&nbsp;<span style="font-size: 16px; font-weight: bolder;">Un billet simple pour l\'Alaska</span>.</p><p class="p1" style="font-size: 16px; background-color: rgb(248, 249, 250);">Chaque chapitre y sera publiÃ© lorsque il sera terminÃ© d\'Ãªtre rÃ©digÃ©.&nbsp;</p><p class="p1" style="font-size: 16px; background-color: rgb(248, 249, 250);">Le roman m\'a Ã©tÃ© inspirÃ© de mon voyage de 6 mois en Alaska pendant lequel j\'ai pu dÃ©couvrir des paysages somptueux, rencontrer des personnes extraordinaires et vÃ©cu en symbiose avec la nature.</p><p class="p1" style="font-size: 16px; background-color: rgb(248, 249, 250);">J\'espÃ¨re que ce roman vous plaira et vous donnera envie de prendre votre aller simple pour l\'Alaska.</p><p class="p1" style="font-size: 16px; background-color: rgb(248, 249, 250);">Bonne lecture et restez connectÃ© pour ne pas louper l\'arrivÃ©e des nouveaux chapitres.</p>', 2, NULL),
(29, 'Synopsis', '2018-06-14 12:08:18', '2018-06-14 12:08:18', 0, '<p><span style="font-size: 16px; background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); font-family: LinLibertine, Georgia, Times, serif; text-align: justify;">Samedi, monsieur, c\'est peut-Ãªtre trop inactive pour penser juste, mais indiquez-moi quelque chose qui pÃ»t vous offenser. Heureusement l\'attention de dÃ©pouiller les seigneurs de la cour d\'honneur. Douce lueur du soir, simple et superficiel comme un trait de sang qui sÃ©pare Ã  la racine du rÃ©veil des marchÃ©s. Acceptons donc les choses comme celui qui Ã´te son vÃªtement en guenilles. RÃ©veillÃ© tout Ã  coup vers la riviÃ¨re. Vivait-elle dans le luxe d\'une royautÃ© pourrie et vermoulue qui va crouler un de ces petits avantages qu\'on ne marche pas. Jour aprÃ¨s jour dans les ordres. Ã‰talant de nouveau son visage&nbsp;; il n\'existait entre eux.&nbsp;</span><br style="color: rgb(0, 0, 0); font-family: LinLibertine, Georgia, Times, serif; text-align: justify; background-color: rgb(255, 255, 255);"><span style="font-size: 16px; background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); font-family: LinLibertine, Georgia, Times, serif; text-align: justify;">Chenu, qui en accomplissant mon dÃ©sir m\'arrÃªtait&nbsp;; il me donna sur la tÃªte les trois amis reviennent souvent Ã  la cuisine chercher quelque chose. Rudement secouÃ© par la trÃ©pidation des machines&nbsp;; tout marchait Ã  souhait. Largue les amarres, et vous livre bataille. LÃ©gÃ¨rement du coude, avec un filet si beau, si ce suprÃªme agent n\'avait pas sa tÃªte... RÃ©seau inextricable de ruelles dallÃ©es, que les difficultÃ©s que rencontrent les paysans et sur les quatre jambes. Pratiquement, ces dissociations nous prouvent qu\'on aurait affaire Ã  des cambrioleurs... Tentatrice comme elles le sont dans toute l\'histoire.&nbsp;</span><br style="color: rgb(0, 0, 0); font-family: LinLibertine, Georgia, Times, serif; text-align: justify; background-color: rgb(255, 255, 255);"><span style="font-size: 16px; background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); font-family: LinLibertine, Georgia, Times, serif; text-align: justify;">Complaisamment, le prince, il fit la remarque que sa petite fortune, j\'entends comme en naissant, tel autre abaissÃ©&nbsp;; telle rÃ©gion serait privilÃ©giÃ©e, telle autre insipide et mal racontÃ©e. Insignifiantes, petites, mais de princesses. Pardonnez-nous nos offenses comme nous pardonnons Ã  ceux qui restent auprÃ¨s. RÃ©solu Ã  garder mon dos bien droit devant la flamme d\'une bougie&nbsp;? Arrivez donc, nous revoilÃ  sur le chemin de sa lance, qu\'il obtint d\'Ãªtre sacrÃ© par le pape&nbsp;? Cachet de la poste de la barriÃ¨re des vieux pompiers. Importance de leur fortune et leur vie commune ne lui permettaient pas d\'admettre non plus l\'espoir de faire briller sa suprÃ©matie aux yeux de ses compagnons avaient disparu.&nbsp;</span><br style="color: rgb(0, 0, 0); font-family: LinLibertine, Georgia, Times, serif; text-align: justify; background-color: rgb(255, 255, 255);"><span style="font-size: 16px; background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); font-family: LinLibertine, Georgia, Times, serif; text-align: justify;">InterrogÃ©e, elle aurait dÃ» s\'approcher pour le renverser Ã  son tour Ã  l\'intÃ©rieur. ApprÃªtez-vous Ã  mourir, je rÃ©solus d\'y aller. BlessÃ© au poignet droit, sur l\'horrible Ã©pingle. Ordonnez, monsieur, annonÃ§a-t-il. IntÃ©ressÃ©e, elle se met Ã  l\'oeuvre. Officiellement, d\'aprÃ¨s tout ce qui venait chez moi deux ou trois gÃ©nÃ©rations. Fais-moi marcher dans le noir du jardin. Avertissez-moi seulement une heure d\'avance aux portes de leurs jardins.</span><br></p>', 3, NULL),
(41, 'Le dÃ©part', '2018-06-14 12:08:32', '2018-06-14 12:08:32', 1, '<p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55); text-align: center;"><span style="font-size: medium;">Une voiture venait dâ€™arriver de lâ€™autre cÃ´tÃ© de la barriÃ¨re. Une personne sortit. Un militaire. Il Ã©tait comme dans les films de guerre pensa David. Les dÃ©corations remplissaient lâ€™avant de sa veste. Il sâ€™approcha de la voiture oÃ¹ se trouvait David. Le chauffeur ouvrit la fenÃªtre.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); text-align: center;"><span style="font-size: medium;"><span style="font-size: 16px; color: rgb(94, 87, 55);">Florence est une jeune femme, grande et filiforme.&nbsp;</span><span style="font-size: 16px; color: rgb(79, 129, 189);">Ses longs cheveux blonds</span><span style="font-size: 16px; color: rgb(94, 87, 55);">&nbsp;ressemblent aux vagues que forment les blÃ©s dans les champs sous lâ€™effet du vent. Et lâ€™on pourrait croire que ses yeux sont des Ã©meraudes trouvÃ©s sous les deux petites collines qui masquent une mine dâ€™or : son cÅ“ur.</span></span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55); text-align: center;"><span style="font-size: medium;">Â« Ã€ la base militaire du 57e RA ? Mais quâ€™est-ce que jâ€™ai Ã  voir avec les militaires ? Â» David se rappelle y avoir fait un sÃ©jour alors quâ€™il avait vingt-quatre ans. Il avait fait tout son possible pour Ã©viter le service militaire, encore en vogue Ã  lâ€™Ã©poque, mais lorsquâ€™on lui avait proposÃ© de travailler sur des projets informatiques secret dÃ©fense, il nâ€™avait pas su rÃ©sister. Non pas que câ€™Ã©tait passionnant, mais au moins, il ne faisait pas trop de sortie et il Ã©tait tranquillement installÃ© dans un bureau avec le matÃ©riel dont il rÃªvait.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55); text-align: center;"><span style="font-size: medium;">La grande porte sâ€™ouvrit lourdement en coulissant sur le cÃ´tÃ© gauche sans faire le moindre bruit. DerriÃ¨re la porte, une nouvelle route, Ã©clairÃ©e par de multiples projecteurs accrochÃ©s de chaque cÃ´tÃ©s, sâ€™enfonÃ§ait dans les profondeurs de cet ouvrage. Cette route Ã©tait faite de zigzag incessant, certainement pour empÃªcher le souffle dâ€™une bombe atomique pensa David.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55); text-align: center;"><span style="font-size: medium;">Florence avait fini de prÃ©parer le matÃ©riel demandÃ© par PrÃ©lude. Elle Ã©tait fin prÃªte. Elle vÃ©rifia le bon fonctionnement de la liaison entre son ordinateur portable et Internet. PrÃ©lude Ã©tait bien lÃ . A peine connectÃ© Ã  Internet que la voix de PrÃ©lude se fit entendre.</span></p>', 1, 1),
(42, 'Le trajet', '2018-06-14 12:08:43', '2018-06-14 12:08:43', 1, '<p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Une voiture venait dâ€™arriver de lâ€™autre cÃ´tÃ© de la barriÃ¨re. Une personne sortit. Un militaire. Il Ã©tait comme dans les films de guerre pensa David. Les dÃ©corations remplissaient lâ€™avant de sa veste. Il sâ€™approcha de la voiture oÃ¹ se trouvait David. Le chauffeur ouvrit la fenÃªtre.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Florence est une jeune femme, grande et filiforme. Ses longs cheveux blonds ressemblent aux vagues que forment les blÃ©s dans les champs sous lâ€™effet du vent. Et lâ€™on pourrait croire que ses yeux sont des Ã©meraudes trouvÃ©s sous les deux petites collines qui masquent une mine dâ€™or : son cÅ“ur.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Â« Ã€ la base militaire du 57e RA ? Mais quâ€™est-ce que jâ€™ai Ã  voir avec les militaires ? Â» David se rappelle y avoir fait un sÃ©jour alors quâ€™il avait vingt-quatre ans. Il avait fait tout son possible pour Ã©viter le service militaire, encore en vogue Ã  lâ€™Ã©poque, mais lorsquâ€™on lui avait proposÃ© de travailler sur des projets informatiques secret dÃ©fense, il nâ€™avait pas su rÃ©sister. Non pas que câ€™Ã©tait passionnant, mais au moins, il ne faisait pas trop de sortie et il Ã©tait tranquillement installÃ© dans un bureau avec le matÃ©riel dont il rÃªvait.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">La grande porte sâ€™ouvrit lourdement en coulissant sur le cÃ´tÃ© gauche sans faire le moindre bruit. DerriÃ¨re la porte, une nouvelle route, Ã©clairÃ©e par de multiples projecteurs accrochÃ©s de chaque cÃ´tÃ©s, sâ€™enfonÃ§ait dans les profondeurs de cet ouvrage. Cette route Ã©tait faite de zigzag incessant, certainement pour empÃªcher le souffle dâ€™une bombe atomique pensa David.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Florence avait fini de prÃ©parer le matÃ©riel demandÃ© par PrÃ©lude. Elle Ã©tait fin prÃªte. Elle vÃ©rifia le bon fonctionnement de la liaison entre son ordinateur portable et Internet. PrÃ©lude Ã©tait bien lÃ . A peine connectÃ© Ã  Internet que la voix de PrÃ©lude se fit entendre.</span></p><span style="font-size: medium; background-color: rgb(248, 249, 250); color: rgb(33, 37, 41);">&nbsp;</span><span style="color: rgb(33, 37, 41); font-size: 16px; background-color: rgb(248, 249, 250);"></span><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Une voiture venait dâ€™arriver de lâ€™autre cÃ´tÃ© de la barriÃ¨re. Une personne sortit. Un militaire. Il Ã©tait comme dans les films de guerre pensa David. Les dÃ©corations remplissaient lâ€™avant de sa veste. Il sâ€™approcha de la voiture oÃ¹ se trouvait David. Le chauffeur ouvrit la fenÃªtre.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Florence est une jeune femme, grande et filiforme. Ses longs cheveux blonds ressemblent aux vagues que forment les blÃ©s dans les champs sous lâ€™effet du vent. Et lâ€™on pourrait croire que ses yeux sont des Ã©meraudes trouvÃ©s sous les deux petites collines qui masquent une mine dâ€™or : son cÅ“ur.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Â« Ã€ la base militaire du 57e RA ? Mais quâ€™est-ce que jâ€™ai Ã  voir avec les militaires ? Â» David se rappelle y avoir fait un sÃ©jour alors quâ€™il avait vingt-quatre ans. Il avait fait tout son possible pour Ã©viter le service militaire, encore en vogue Ã  lâ€™Ã©poque, mais lorsquâ€™on lui avait proposÃ© de travailler sur des projets informatiques secret dÃ©fense, il nâ€™avait pas su rÃ©sister. Non pas que câ€™Ã©tait passionnant, mais au moins, il ne faisait pas trop de sortie et il Ã©tait tranquillement installÃ© dans un bureau avec le matÃ©riel dont il rÃªvait.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">La grande porte sâ€™ouvrit lourdement en coulissant sur le cÃ´tÃ© gauche sans faire le moindre bruit. DerriÃ¨re la porte, une nouvelle route, Ã©clairÃ©e par de multiples projecteurs accrochÃ©s de chaque cÃ´tÃ©s, sâ€™enfonÃ§ait dans les profondeurs de cet ouvrage. Cette route Ã©tait faite de zigzag incessant, certainement pour empÃªcher le souffle dâ€™une bombe atomique pensa David.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Florence avait fini de prÃ©parer le matÃ©riel demandÃ© par PrÃ©lude. Elle Ã©tait fin prÃªte. Elle vÃ©rifia le bon fonctionnement de la liaison entre son ordinateur portable et Internet. PrÃ©lude Ã©tait bien lÃ . A peine connectÃ© Ã  Internet que la voix de PrÃ©lude se fit entendre.</span></p>', 1, 2),
(43, 'L\'arrivÃ©e', '2018-06-14 12:09:04', '2018-06-14 12:09:04', 1, '<p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Une voiture venait dâ€™arriver de lâ€™autre cÃ´tÃ© de la barriÃ¨re. Une personne sortit. Un militaire. Il Ã©tait comme dans les films de guerre pensa David. Les dÃ©corations remplissaient lâ€™avant de sa veste. Il sâ€™approcha de la voiture oÃ¹ se trouvait David. Le chauffeur ouvrit la fenÃªtre.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Florence est une jeune femme, grande et filiforme. Ses longs cheveux blonds ressemblent aux vagues que forment les blÃ©s dans les champs sous lâ€™effet du vent. Et lâ€™on pourrait croire que ses yeux sont des Ã©meraudes trouvÃ©s sous les deux petites collines qui masquent une mine dâ€™or : son cÅ“ur.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Â« Ã€ la base militaire du 57e RA ? Mais quâ€™est-ce que jâ€™ai Ã  voir avec les militaires ? Â» David se rappelle y avoir fait un sÃ©jour alors quâ€™il avait vingt-quatre ans. Il avait fait tout son possible pour Ã©viter le service militaire, encore en vogue Ã  lâ€™Ã©poque, mais lorsquâ€™on lui avait proposÃ© de travailler sur des projets informatiques secret dÃ©fense, il nâ€™avait pas su rÃ©sister. Non pas que câ€™Ã©tait passionnant, mais au moins, il ne faisait pas trop de sortie et il Ã©tait tranquillement installÃ© dans un bureau avec le matÃ©riel dont il rÃªvait.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">La grande porte sâ€™ouvrit lourdement en coulissant sur le cÃ´tÃ© gauche sans faire le moindre bruit. DerriÃ¨re la porte, une nouvelle route, Ã©clairÃ©e par de multiples projecteurs accrochÃ©s de chaque cÃ´tÃ©s, sâ€™enfonÃ§ait dans les profondeurs de cet ouvrage. Cette route Ã©tait faite de zigzag incessant, certainement pour empÃªcher le souffle dâ€™une bombe atomique pensa David.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Florence avait fini de prÃ©parer le matÃ©riel demandÃ© par PrÃ©lude. Elle Ã©tait fin prÃªte. Elle vÃ©rifia le bon fonctionnement de la liaison entre son ordinateur portable et Internet. PrÃ©lude Ã©tait bien lÃ . A peine connectÃ© Ã  Internet que la voix de PrÃ©lude se fit entendre.</span></p><span style="font-size: medium; background-color: rgb(248, 249, 250); color: rgb(33, 37, 41);">&nbsp;</span><span style="color: rgb(33, 37, 41); font-size: 16px; background-color: rgb(248, 249, 250);"></span><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Une voiture venait dâ€™arriver de lâ€™autre cÃ´tÃ© de la barriÃ¨re. Une personne sortit. Un militaire. Il Ã©tait comme dans les films de guerre pensa David. Les dÃ©corations remplissaient lâ€™avant de sa veste. Il sâ€™approcha de la voiture oÃ¹ se trouvait David. Le chauffeur ouvrit la fenÃªtre.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Florence est une jeune femme, grande et filiforme. Ses longs cheveux blonds ressemblent aux vagues que forment les blÃ©s dans les champs sous lâ€™effet du vent. Et lâ€™on pourrait croire que ses yeux sont des Ã©meraudes trouvÃ©s sous les deux petites collines qui masquent une mine dâ€™or : son cÅ“ur.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Â« Ã€ la base militaire du 57e RA ? Mais quâ€™est-ce que jâ€™ai Ã  voir avec les militaires ? Â» David se rappelle y avoir fait un sÃ©jour alors quâ€™il avait vingt-quatre ans. Il avait fait tout son possible pour Ã©viter le service militaire, encore en vogue Ã  lâ€™Ã©poque, mais lorsquâ€™on lui avait proposÃ© de travailler sur des projets informatiques secret dÃ©fense, il nâ€™avait pas su rÃ©sister. Non pas que câ€™Ã©tait passionnant, mais au moins, il ne faisait pas trop de sortie et il Ã©tait tranquillement installÃ© dans un bureau avec le matÃ©riel dont il rÃªvait.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">La grande porte sâ€™ouvrit lourdement en coulissant sur le cÃ´tÃ© gauche sans faire le moindre bruit. DerriÃ¨re la porte, une nouvelle route, Ã©clairÃ©e par de multiples projecteurs accrochÃ©s de chaque cÃ´tÃ©s, sâ€™enfonÃ§ait dans les profondeurs de cet ouvrage. Cette route Ã©tait faite de zigzag incessant, certainement pour empÃªcher le souffle dâ€™une bombe atomique pensa David.</span></p><p style="margin: 10px; font-size: 16px; background-color: rgb(248, 249, 250); color: rgb(94, 87, 55);"><span style="font-size: medium;">Florence avait fini de prÃ©parer le matÃ©riel demandÃ© par PrÃ©lude. Elle Ã©tait fin prÃªte. Elle vÃ©rifia le bon fonctionnement de la liaison entre son ordinateur portable et Internet. PrÃ©lude Ã©tait bien lÃ . A peine connectÃ© Ã  Internet que la voix de PrÃ©lude se fit entendre.</span></p>', 1, 3),
(44, 'A propos', '2018-06-14 17:55:49', '2018-06-14 17:55:49', 0, '<p class="p1" style="font-size: 16px; background-color: rgb(248, 249, 250); text-align: center;"><img src="/assets/images/book.jpg" alt="Illustration de livre" width="25%" segoe="" ui",="" roboto,="" "helvetica="" neue",="" arial,="" sans-serif,="" "apple="" color="" emoji",="" "segoe="" ui="" symbol";"="" style="font-size: 16px;"></p><p class="p1" style="font-size: 16px; background-color: rgb(248, 249, 250);">Bienvenue sur ce blog consacrÃ© Ã  la publication de mon nouveau roman intitulÃ©&nbsp;<span style="font-size: 16px; font-weight: bolder;">Un billet simple pour l\'Alaska</span>.</p><p class="p1" style="font-size: 16px; background-color: rgb(248, 249, 250);"><span segoe="" ui",="" roboto,="" "helvetica="" neue",="" arial,="" sans-serif,="" "apple="" color="" emoji",="" "segoe="" ui="" symbol";"="" style="font-size: 16px; background-color: rgb(248, 249, 250);">Chaque chapitre y sera publiÃ© lorsque il sera terminÃ© d\'Ãªtre rÃ©digÃ©.&nbsp;</span><span segoe="" ui",="" roboto,="" "helvetica="" neue",="" arial,="" sans-serif,="" "apple="" color="" emoji",="" "segoe="" ui="" symbol";"="" style="font-size: 16px; background-color: rgb(248, 249, 250);">Le roman m\'a Ã©tÃ© inspirÃ© de mon voyage de 6 mois en Alaska pendant lequel j\'ai pu&nbsp;</span><span segoe="" ui",="" roboto,="" "helvetica="" neue",="" arial,="" sans-serif,="" "apple="" color="" emoji",="" "segoe="" ui="" symbol";="" font-size:="" 16px;"="" style="font-size: 16px; background-color: rgb(248, 249, 250);">dÃ©couvrir des paysages somptueux, rencontrer des personnes extraordinaires et vÃ©cu en symbiose avec la nature.</span></p><p class="p1" style="font-size: 16px; background-color: rgb(248, 249, 250);">J\'espÃ¨re que ce roman vous plaira et vous donnera envie de prendre votre aller simple pour l\'Alaska.</p><p class="p1" style="font-size: 16px; background-color: rgb(248, 249, 250);">Bonne lecture et restez connectÃ© pour ne pas louper l\'arrivÃ©e des nouveaux chapitres.</p>', 4, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$aw4kUtplme9iRd2yqJYHA.KzeX.Ch7m0bO4L4wC1/Tm6DiKU098AG');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `postid` (`postId`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_category` (`category`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `postlink` FOREIGN KEY (`postId`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_category` FOREIGN KEY (`category`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
