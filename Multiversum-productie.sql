-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Gegenereerd op: 25 jun 2017 om 19:16
-- Serverversie: 5.5.55-0+deb8u1
-- PHP-versie: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `multiversum`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `betaal_methode`
--

CREATE TABLE IF NOT EXISTS `betaal_methode` (
  `idbetaal_methode` int(11) NOT NULL,
  `betaalmethode` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Categorie`
--

CREATE TABLE IF NOT EXISTS `Categorie` (
`idCategorie` int(11) NOT NULL,
  `naam` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `Categorie`
--

INSERT INTO `Categorie` (`idCategorie`, `naam`) VALUES
(1, 'VR');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Fabrikant`
--

CREATE TABLE IF NOT EXISTS `Fabrikant` (
`idFabrikant` int(11) NOT NULL,
  `naam` varchar(80) NOT NULL,
  `straat` varchar(100) DEFAULT NULL,
  `nummer` int(11) DEFAULT NULL,
  `postcode` char(6) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `telefoonnummer` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `files`
--

CREATE TABLE IF NOT EXISTS `files` (
`idfiles` int(11) NOT NULL,
  `filenaam` varchar(45) NOT NULL,
  `pad` text,
  `extensie` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `files`
--

INSERT INTO `files` (`idfiles`, `filenaam`, `pad`, `extensie`) VALUES
(123, '2001214983.jpeg', 'file/uploads/', NULL),
(124, '2000898912.png', 'file/uploads/', NULL),
(125, '2001229257-2.jpeg', 'file/uploads/', NULL),
(126, '2000774356.png', 'file/uploads/', NULL),
(127, '2001357491.jpeg', 'file/uploads/', NULL),
(128, '2000566018 (1).png', 'file/uploads/', NULL),
(129, '2001139019.png', 'file/uploads/', NULL),
(130, '2000949925 (1).jpeg', 'file/uploads/', NULL),
(131, '2001122203.jpeg', 'file/uploads/', NULL),
(132, '2001184369.jpeg', 'file/uploads/', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `files_has_Product`
--

CREATE TABLE IF NOT EXISTS `files_has_Product` (
  `files_idfiles` int(11) NOT NULL,
  `Product_idProduct` int(11) NOT NULL,
`idfiles_has_Product` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `files_has_Product`
--

INSERT INTO `files_has_Product` (`files_idfiles`, `Product_idProduct`, `idfiles_has_Product`) VALUES
(123, 77, 124),
(124, 78, 125),
(125, 79, 126),
(126, 80, 127),
(127, 81, 128),
(128, 82, 129),
(129, 83, 130),
(130, 84, 131),
(131, 85, 132),
(132, 86, 133);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Order`
--

CREATE TABLE IF NOT EXISTS `Order` (
  `idOrder` int(11) NOT NULL,
  `klant_voornaam` varchar(45) NOT NULL,
  `klant_achternaam` varchar(45) NOT NULL,
  `klant_tussenvoegsel` varchar(45) DEFAULT NULL,
  `klant_straat` varchar(45) NOT NULL,
  `klant_huisnummer` varchar(45) NOT NULL,
  `klant_postcode` varchar(45) NOT NULL,
  `klant_email` varchar(45) NOT NULL,
  `order_status` varchar(45) DEFAULT NULL,
  `betaal_status` varchar(85) DEFAULT NULL,
  `betaal_methode_idbetaal_methode` int(11) DEFAULT NULL,
  `paymentID` varchar(85) DEFAULT NULL,
  `klant_huisnummertoevoegingen` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `Order`
--

INSERT INTO `Order` (`idOrder`, `klant_voornaam`, `klant_achternaam`, `klant_tussenvoegsel`, `klant_straat`, `klant_huisnummer`, `klant_postcode`, `klant_email`, `order_status`, `betaal_status`, `betaal_methode_idbetaal_methode`, `paymentID`, `klant_huisnummertoevoegingen`) VALUES
(1, 'Peter', 'Romijn', '', 'Meester Tydemanstraat', '23', '4001 CR', 'peter@romijn-kuipers.nl', 'in behandeling', 'paid', NULL, 'tr_J7FyRNhceU', '');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `order_item`
--

CREATE TABLE IF NOT EXISTS `order_item` (
  `idorder_item` int(11) NOT NULL,
  `Order_idOrder` int(11) NOT NULL,
  `prijs` decimal(18,2) NOT NULL,
  `Product_idProduct` int(11) NOT NULL,
  `aantal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Product`
--

CREATE TABLE IF NOT EXISTS `Product` (
`idProduct` int(11) NOT NULL,
  `Fabrikant_idFabrikant` int(11) DEFAULT NULL,
  `naam` varchar(45) NOT NULL,
  `prijs` decimal(18,2) NOT NULL,
  `beschrijving` text NOT NULL,
  `EAN` varchar(255) DEFAULT NULL,
  `Categorie_idCategorie` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `Product`
--

INSERT INTO `Product` (`idProduct`, `Fabrikant_idFabrikant`, `naam`, `prijs`, `beschrijving`, `EAN`, `Categorie_idCategorie`, `status`) VALUES
(77, NULL, 'HTC Vive', 899.00, '&amp;lt;p&amp;gt;De HTC Vive is een virtual reality bril met bewegingscontrollers die je met je pc of laptop gebruikt.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Met deze VR bril beleef je virtuele werelden in de hoogste kwaliteit. Met de bijgeleverde bewegingscontrollers en basisstations zie je jouw bewegingen in VR verschijnen. Zo pak je eenvoudig een object op en loop je er de virtuele ruimte mee door. Wees niet bang dat je tegen een muur aan loopt, het ingebouwde chaperone camerasysteem waarschuwt je wanneer je het einde van de speelruimte nadert.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Verplaats jezelf naar de top van de Mount Everest, verdedig jezelf tegen een horde zombies of kijk een film op een gesimuleerd bioscoopscherm.&amp;lt;/p&amp;gt;', '0471848768757', 1, 1),
(78, NULL, 'Oculus Rift', 749.00, '&amp;lt;p&amp;gt;Met de Oculus Rift ervaar je jou games, VR films en andere multimedia zoals je dat nog nooit gedaan hebt. Stap echt in je spel en ervaar deze alsof je er echt bij bent.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Gecombineerd met het tracking systeem wordt je echt in je media getrokken. De Rift heeft een geavanceerd en geratineerd ontwerp waardoor deze comfortabel op je hoofd zit.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;De hoofdband is eenvoudig aan te passen, zodat de Rift voor iedereen passend te maken is.&amp;lt;/p&amp;gt;', '8158200200118', 1, 1),
(79, NULL, 'SAMSUNG GEAR VR 2', 84.99, '&amp;lt;p&amp;gt;Met de Samsung Gear VR 2 brengen Samsung en Oculus de wereld van VR games en video&amp;amp;#39;s naar je telefoon.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Klik je (geschikte) Samsung telefoon in de voorkant van de bril, waarna deze als scherm dient. Je bedient de bril met het trackpad aan de zijkant. Nieuw aan het trackpad is de fysieke &amp;amp;#39;home&amp;amp;#39; knop, waardoor je makkelijk naar het menu schakelt. Ook de lens is verbeterd, hierdoor is hij beter af te stellen en zijn de graphics scherper.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Jouw kijkrichting bepaalt wat je op dat moment ziet: kijk je omhoog dan zie je bijvoorbeeld de lucht, kijk je naar beneden dan zie je grond waar je op &amp;amp;#39;staat&amp;amp;#39;. Dit laat je films en games vanuit elke hoek beleven.&amp;lt;/p&amp;gt;', '8806088503141', 1, 1),
(80, NULL, 'Sony Playstation VR', 388.00, '&amp;lt;p&amp;gt;Beleef games alsof je er middenin zit met deze Sony PlayStation VR virtual reality gaming-bril.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;De bril laat je op een geheel nieuwe manier games ervaren waarbij de speler bijna letterlijk deel uit maakt van de actie. Met een helder OLED-scherm wat zorgt voor een vloeiend beeld van 120 Hz heb je 360 graden zicht om je omgeving te verkennen, daarnaast beschikt de PS VR over 3D-audio waardoor het geluid ook met je meedraait als je om je heen kijkt. Het ontwerp van de PlayStation VR is zo gemaakt dat je als speler bijna niet merkt dat hij er is, licht, comfortabel en gemakkelijk op- en af te zetten.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;De PlayStation VR is verder voorzien van handige en leuke functies, met het PlayStation VR Social-scherm kun je vrienden laten zien wat jij ziet, direct op de televisie en met de Cinematic Mode kun je ook spellen en films afspelen die niet specifiek voor de PS VR ontwikkeld zijn. Het enige wat je nodig hebt om de PlayStation VR te gebruiken is natuurlijk een PlayStation 4 met DualShock 4- of PlayStation Move-controller en de PlayStation Camera.&amp;lt;/p&amp;gt;', '0711719843757', 1, 1),
(81, NULL, 'HTC Vive Business Edition', 1399.00, '&amp;lt;p&amp;gt;&amp;lt;strong&amp;gt;D&amp;amp;eacute; headset voor je ogen&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\nWanneer je de Vive headset opzet, word je meteen omringd door een virtuele wereld vol verrassingen! Adembenemende visuele ervaringen worden geleverd door een headset met een 110 &amp;amp;deg; zichtsveld, waardoor je bovendien geen last hebt van dode hoeken. De resolutie van 2160 x 1200 en 90 Hz verversingsfrequentie leveren gedetailleerde graphics en levensechte, realistische beweging voor een soepele gameplay.&amp;amp;nbsp;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Volledige controle&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\nInteractie wordt verzorgd met behulp van twee draadloze controllers, elk met HD haptic feedback, dual-stage triggers en multifunctionele trackpads. Elke controller is voorzien van 24 sensoren voor 360 &amp;amp;deg; &amp;amp;eacute;&amp;amp;eacute;n-op-&amp;amp;eacute;&amp;amp;eacute;n tracking die bewegingen van de hand weerspiegelt.&amp;amp;nbsp;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Twee basisstations voor meer vrijheid&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\nRoom-scale motion tracking is ingeschakeld via twee basisstations die draadloos contact maken. Hierdoor zijn extra snoeren niet nodig en heb je volledige vrijheid om te bewegen. Het ingebouwde chaperone camerasysteem waarschuwt je wanneer je het einde van de speelruimte nadert.&amp;amp;nbsp;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;Inclusief Dedicated Business Edition klantenservice&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\nVoor zakelijke partners is een speciale klantenservice beschikbaar voor hulp en ondersteuning.&amp;amp;nbsp;&amp;lt;br /&amp;gt;\r\n&amp;lt;br /&amp;gt;\r\n&amp;lt;strong&amp;gt;VIVE Business Edition 12-maanden garantie&amp;lt;/strong&amp;gt;&amp;lt;br /&amp;gt;\r\nVoor de HTC Vive Business Edition geldt een uitgebreide en aangepaste garantie/ service van 12 maanden. Professionals profiteren o.a. van een snelle reparatie bij een defect. kijk voor meer details op www.htc.com.&amp;lt;/p&amp;gt;', '4718487692866', 1, 1),
(82, NULL, 'Homido Smartphone Virtual Reality Headset', 39.95, '&amp;lt;p&amp;gt;Virtual Reality is bijzonder fascinerend, maar is dankzij de schermpjes die in een VR-headset zitten meestal behoorlijk prijzig. Deze Homido Smartphone VR-headset lost dat probleem op door uw smartphone te gebruiken als beeldscherm. U klikt uw ondersteunde smartphone in de smartphone-clip, downloadt de Homido-app en geniet van Virtual Reality.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;De Homido ondersteunt zowel iOS- als Android-apparaten, zoals de iPhone 5S en hoger en de Samsung Galaxy Note3 en hoger. De Homido is in te stellen, zoals afstand tussen de ogen en de afstand tussen uw ogen en het scherm voor de meest comfortabele VR-ervaring.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;De speciale lenzen hebben een groothoek van 100&amp;amp;deg; terwijl uw smartphone u 360&amp;amp;deg; in de rondte laat kijken. Dompel uzelf onder in de wereld van Virtual Reality met Homido.&amp;lt;/p&amp;gt;', '3760071190020', 1, 1),
(83, NULL, 'OSVR Hacker Development Kit 2', 379.00, '&amp;lt;p&amp;gt;&amp;amp;nbsp;&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;OVSR staat voor Open Source Virtual Reality, een standaard voor VR-gaming waaraan diverse grote bedrijven zoals Razer meewerken. Met als enige doel om VR-gaming zo gemakkelijk mogelijk te maken, niet alleen voor producenten van VR-apparatuur en -games, maar vooral voor de gamers die zich graag aan VR-gaming wagen.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;De Razer OVSR Hacker Development Kit 2 is Razers bijdrage aan OVSR. Volgens de fabrikant is de bril vooral ontworpen om een goede VR-ervaring te leveren op mid-range pc&amp;amp;#39;s. Oftewel: in tegenstelling tot bijvoorbeeld de HTC Vive vereist de Razer Razer OVSR Hacker Development Kit geen &amp;amp;uuml;ber-computer om te kunnen functioneren.&amp;lt;/p&amp;gt;', '8886419329022', 1, 1),
(84, NULL, 'VR BOX VR-bril', 11.75, '&amp;lt;p&amp;gt;Beleef jouw favoriete speelfilm of videogame nog intenser met de VR BOX Virtual Reality Bril. De VR BOX is speciaal ontworpen voor smartphones van 4.7 tot en met 6 inch.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Je plaatst het toestel in de VR BOX smartphone houder en jou telefoon wordt omgetoverd naar een eigen 3D bioscoop. De VR BOX Virtual Reality bril beschikt over een comfortabele hoofdband met zacht schuimen randen. Daarnaast is de VR BOX eenvoudig te verstellen naar de meest optimale afstand voor je ogen.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;Bevind je elke keer in een uitzonderlijke omgeving, dankzij de VR BOX Virtual Reality bril.&amp;lt;/p&amp;gt;', '0602561524461', 1, 1),
(85, NULL, 'Zeiss VR One Plus', 121.95, '&amp;lt;p&amp;gt;Met de Carl Zeiss VR ONE Plus geniet je snel en eenvoudig van verschillende VR apps, VR filmpjes en afbeeldingen met je eigen Smartphone.&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;De VR-bril is geschikt voor bijna alle smartphones met een beeldscherm tussen de 4,7 en 5,5 inch. Door de opzet van de VR ONE Plus kunnen brildragers hun bril gewoon ophouden.&amp;lt;/p&amp;gt;', '4047865190398', 1, 1),
(86, NULL, 'Wonky Monkey 3D VR Glasses', 17.98, '&amp;lt;p&amp;gt;elletjes in virtual reality of bekijk je favoriete films en series met deze VR bril!&amp;amp;nbsp;&amp;lt;/p&amp;gt;\r\n\r\n&amp;lt;p&amp;gt;&amp;lt;br /&amp;gt;\r\nDankzij de verstelbare, elastieken band zit de bril altijd prettig op je hoofd en daarnaast zijn de lenzen geheel naar wens aan te passen.&amp;lt;/p&amp;gt;', '8718924811184', 1, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Specificatie`
--

CREATE TABLE IF NOT EXISTS `Specificatie` (
`idSpecificatie` int(11) NOT NULL,
  `Specificatie_naam` varchar(80) DEFAULT NULL,
  `Specificatie_waarde` varchar(45) DEFAULT NULL,
  `Product_idProduct` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`iduser` int(11) NOT NULL,
  `email` varchar(45) NOT NULL,
  `wachtwoord` varchar(100) NOT NULL,
  `groep` varchar(85) NOT NULL,
  `voornaam` varchar(45) NOT NULL,
  `achternaam` varchar(45) NOT NULL,
  `tussenvoegsel` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`iduser`, `email`, `wachtwoord`, `groep`, `voornaam`, `achternaam`, `tussenvoegsel`) VALUES
(1, 'admin@multiversum.nl', '$2y$10$eET5kpmqZL0CBiVpxtB9xOg.PPIIB41FsnafBcsywl.ZTvM4dzN.u', 'admin', '', '', NULL);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `betaal_methode`
--
ALTER TABLE `betaal_methode`
 ADD PRIMARY KEY (`idbetaal_methode`);

--
-- Indexen voor tabel `Categorie`
--
ALTER TABLE `Categorie`
 ADD PRIMARY KEY (`idCategorie`);

--
-- Indexen voor tabel `Fabrikant`
--
ALTER TABLE `Fabrikant`
 ADD PRIMARY KEY (`idFabrikant`);

--
-- Indexen voor tabel `files`
--
ALTER TABLE `files`
 ADD PRIMARY KEY (`idfiles`);

--
-- Indexen voor tabel `files_has_Product`
--
ALTER TABLE `files_has_Product`
 ADD PRIMARY KEY (`idfiles_has_Product`), ADD KEY `fk_files_has_Product_Product1_idx` (`Product_idProduct`), ADD KEY `fk_files_has_Product_files1_idx` (`files_idfiles`);

--
-- Indexen voor tabel `Product`
--
ALTER TABLE `Product`
 ADD PRIMARY KEY (`idProduct`), ADD KEY `fk_Product_Fabrikant_idx` (`Fabrikant_idFabrikant`), ADD KEY `fk_Product_Categorie1_idx` (`Categorie_idCategorie`);

--
-- Indexen voor tabel `Specificatie`
--
ALTER TABLE `Specificatie`
 ADD PRIMARY KEY (`idSpecificatie`), ADD KEY `fk_Specificatie_Product1_idx` (`Product_idProduct`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`iduser`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `Categorie`
--
ALTER TABLE `Categorie`
MODIFY `idCategorie` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT voor een tabel `Fabrikant`
--
ALTER TABLE `Fabrikant`
MODIFY `idFabrikant` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `files`
--
ALTER TABLE `files`
MODIFY `idfiles` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=133;
--
-- AUTO_INCREMENT voor een tabel `files_has_Product`
--
ALTER TABLE `files_has_Product`
MODIFY `idfiles_has_Product` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=134;
--
-- AUTO_INCREMENT voor een tabel `Product`
--
ALTER TABLE `Product`
MODIFY `idProduct` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=87;
--
-- AUTO_INCREMENT voor een tabel `Specificatie`
--
ALTER TABLE `Specificatie`
MODIFY `idSpecificatie` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `files_has_Product`
--
ALTER TABLE `files_has_Product`
ADD CONSTRAINT `fk_files_has_Product_files1` FOREIGN KEY (`files_idfiles`) REFERENCES `files` (`idfiles`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_files_has_Product_Product1` FOREIGN KEY (`Product_idProduct`) REFERENCES `Product` (`idProduct`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `Product`
--
ALTER TABLE `Product`
ADD CONSTRAINT `fk_Product_Categorie1` FOREIGN KEY (`Categorie_idCategorie`) REFERENCES `Categorie` (`idCategorie`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_Product_Fabrikant` FOREIGN KEY (`Fabrikant_idFabrikant`) REFERENCES `Fabrikant` (`idFabrikant`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `Specificatie`
--
ALTER TABLE `Specificatie`
ADD CONSTRAINT `fk_Specificatie_Product1` FOREIGN KEY (`Product_idProduct`) REFERENCES `Product` (`idProduct`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
