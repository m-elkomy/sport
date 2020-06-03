-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2018 at 02:22 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sport`
--

-- --------------------------------------------------------

--
-- Table structure for table `card_type`
--

CREATE TABLE `card_type` (
  `CardID` int(11) NOT NULL,
  `Card_Type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `card_type`
--

INSERT INTO `card_type` (`CardID`, `Card_Type`) VALUES
(1, 'Yellow'),
(2, 'Red');

-- --------------------------------------------------------

--
-- Table structure for table `coach`
--

CREATE TABLE `coach` (
  `CoachID` int(11) NOT NULL,
  `CoachName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `coach`
--

INSERT INTO `coach` (`CoachID`, `CoachName`) VALUES
(1, 'Kamal'),
(2, 'Samir'),
(3, 'Mounir'),
(4, 'moustafa');

-- --------------------------------------------------------

--
-- Table structure for table `continent`
--

CREATE TABLE `continent` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `continent`
--

INSERT INTO `continent` (`ID`, `Name`) VALUES
(1, 'Asia'),
(2, 'Africa'),
(3, 'America'),
(4, 'Euorope'),
(5, 'world');

-- --------------------------------------------------------

--
-- Table structure for table `continent_cup`
--

CREATE TABLE `continent_cup` (
  `ID` int(11) NOT NULL,
  `CupID` int(11) NOT NULL,
  `Con_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `continent_cup`
--

INSERT INTO `continent_cup` (`ID`, `CupID`, `Con_ID`) VALUES
(1, 1, 2),
(2, 2, 1),
(3, 3, 3),
(4, 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `continent_team`
--

CREATE TABLE `continent_team` (
  `ID` int(11) NOT NULL,
  `C_ID` int(11) NOT NULL,
  `T_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `continent_team`
--

INSERT INTO `continent_team` (`ID`, `C_ID`, `T_ID`) VALUES
(1, 2, 1),
(2, 1, 2),
(3, 3, 3),
(4, 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `cup`
--

CREATE TABLE `cup` (
  `CupID` int(11) NOT NULL,
  `CupName` varchar(255) NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cup`
--

INSERT INTO `cup` (`CupID`, `CupName`, `StartDate`, `EndDate`) VALUES
(1, 'African Cup', '2018-07-06', '2018-07-28'),
(2, 'Asian cup', '2018-07-01', '2018-07-02'),
(3, 'american cup', '2018-07-01', '2018-07-01'),
(4, 'euoropian cup', '2018-07-01', '2018-07-01');

-- --------------------------------------------------------

--
-- Table structure for table `cup_schdule`
--

CREATE TABLE `cup_schdule` (
  `ID` int(11) NOT NULL,
  `CupID` int(11) NOT NULL,
  `Team1ID` int(11) NOT NULL,
  `Team2ID` int(11) NOT NULL,
  `MatchDate` date NOT NULL,
  `MatchTime` time NOT NULL,
  `StadiumID` int(11) NOT NULL,
  `Round` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cup_schdule`
--

INSERT INTO `cup_schdule` (`ID`, `CupID`, `Team1ID`, `Team2ID`, `MatchDate`, `MatchTime`, `StadiumID`, `Round`) VALUES
(1, 1, 1, 1, '2018-07-18', '11:58:00', 1, 1),
(2, 2, 2, 2, '2018-07-01', '04:00:00', 2, 1),
(3, 3, 3, 3, '2018-07-29', '14:01:00', 1, 2),
(4, 4, 4, 4, '2018-07-01', '02:00:00', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cup_team`
--

CREATE TABLE `cup_team` (
  `Cup_TeamID` int(11) NOT NULL,
  `CupID` int(11) NOT NULL,
  `TeamID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cup_team`
--

INSERT INTO `cup_team` (`Cup_TeamID`, `CupID`, `TeamID`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `match_cards`
--

CREATE TABLE `match_cards` (
  `MatchCardID` int(11) NOT NULL,
  `MatchID` int(11) NOT NULL,
  `PlayerID` int(11) NOT NULL,
  `CardTime` time NOT NULL,
  `CardID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `match_cards`
--

INSERT INTO `match_cards` (`MatchCardID`, `MatchID`, `PlayerID`, `CardTime`, `CardID`) VALUES
(1, 1, 1, '01:00:00', 1),
(2, 2, 2, '01:00:00', 2),
(3, 3, 3, '22:58:00', 2),
(4, 4, 4, '01:00:00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `match_goal`
--

CREATE TABLE `match_goal` (
  `MatchGoalID` int(11) NOT NULL,
  `MatchID` int(11) NOT NULL,
  `PlayerID` int(11) NOT NULL,
  `GoalTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `match_goal`
--

INSERT INTO `match_goal` (`MatchGoalID`, `MatchID`, `PlayerID`, `GoalTime`) VALUES
(1, 1, 1, '02:01:00'),
(2, 2, 2, '00:00:00'),
(3, 3, 3, '03:00:00'),
(4, 4, 4, '01:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE `player` (
  `PlayerID` int(11) NOT NULL,
  `PlayerName` varchar(255) NOT NULL,
  `PlayerNumber` tinyint(4) NOT NULL,
  `DOB` date NOT NULL,
  `TeamID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`PlayerID`, `PlayerName`, `PlayerNumber`, `DOB`, `TeamID`) VALUES
(1, 'Traka', 22, '2018-07-13', 1),
(2, 'cristiano', 7, '2018-07-04', 2),
(3, 'hary kane', 31, '2018-07-12', 3),
(4, 'sadio mane', 20, '2018-07-01', 4);

-- --------------------------------------------------------

--
-- Table structure for table `stadium`
--

CREATE TABLE `stadium` (
  `StadiumID` int(11) NOT NULL,
  `StadiumName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stadium`
--

INSERT INTO `stadium` (`StadiumID`, `StadiumName`) VALUES
(1, 'wembly1'),
(2, 'santiago');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `TeamID` int(11) NOT NULL,
  `TeamName` varchar(255) NOT NULL,
  `CoachID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`TeamID`, `TeamName`, `CoachID`) VALUES
(1, 'El-Ahly', 1),
(2, 'Real-madrid', 2),
(3, 'Real-swisdad', 3),
(4, 'atlitico', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `RegStatus` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UserName`, `Email`, `FullName`, `image`, `Password`, `RegStatus`) VALUES
(2, 'mohamed', 'mohamed@mohamed.com', 'mohamed elkomy', '', '292959f6c7ab4f8b0761469ac1f11fc73f43b306', '2018-07-01'),
(3, 'Kamal', 'k@k.com', 'kamal mahmoud', '', 'b4c27cacc905cd1ab9c632a08694609a7c3fec0b', '2018-07-19'),
(4, 'Hany ', 'h@h.com', 'hany matter', '', '87e66e355faadf81e8495156d9340002413ced2f', '2018-07-19'),
(5, 'Yousef', 'y@y.com', 'yousef matter', '', 'a265160fa26afc5e899e2dab58956fbc444359de', '2018-07-19'),
(6, 'moustafa', 'm@m.com', 'moustafa elkomy', '', 'f7351f30e42bfffdbc34367e08085f6b59cbdbc9', '2018-07-19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `card_type`
--
ALTER TABLE `card_type`
  ADD PRIMARY KEY (`CardID`);

--
-- Indexes for table `coach`
--
ALTER TABLE `coach`
  ADD PRIMARY KEY (`CoachID`);

--
-- Indexes for table `continent`
--
ALTER TABLE `continent`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `continent_cup`
--
ALTER TABLE `continent_cup`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `continent3` (`CupID`),
  ADD KEY `continent4` (`Con_ID`);

--
-- Indexes for table `continent_team`
--
ALTER TABLE `continent_team`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `continent1` (`T_ID`),
  ADD KEY `continent2` (`C_ID`);

--
-- Indexes for table `cup`
--
ALTER TABLE `cup`
  ADD PRIMARY KEY (`CupID`);

--
-- Indexes for table `cup_schdule`
--
ALTER TABLE `cup_schdule`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `cup_schdule1` (`CupID`),
  ADD KEY `stadium1` (`StadiumID`),
  ADD KEY `cupteam1` (`Team1ID`),
  ADD KEY `cupteam2` (`Team2ID`);

--
-- Indexes for table `cup_team`
--
ALTER TABLE `cup_team`
  ADD PRIMARY KEY (`Cup_TeamID`),
  ADD KEY `cup_2` (`CupID`),
  ADD KEY `teamcup` (`TeamID`);

--
-- Indexes for table `match_cards`
--
ALTER TABLE `match_cards`
  ADD PRIMARY KEY (`MatchCardID`),
  ADD KEY `matchcard` (`CardID`),
  ADD KEY `playercard` (`PlayerID`),
  ADD KEY `cardmatch_1` (`MatchID`);

--
-- Indexes for table `match_goal`
--
ALTER TABLE `match_goal`
  ADD PRIMARY KEY (`MatchGoalID`),
  ADD KEY `matchgoal1` (`MatchID`),
  ADD KEY `matchplayer` (`PlayerID`);

--
-- Indexes for table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`PlayerID`),
  ADD KEY `player_1` (`TeamID`);

--
-- Indexes for table `stadium`
--
ALTER TABLE `stadium`
  ADD PRIMARY KEY (`StadiumID`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`TeamID`),
  ADD KEY `coahc_1` (`CoachID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserName` (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `card_type`
--
ALTER TABLE `card_type`
  MODIFY `CardID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `coach`
--
ALTER TABLE `coach`
  MODIFY `CoachID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `continent`
--
ALTER TABLE `continent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `continent_cup`
--
ALTER TABLE `continent_cup`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `continent_team`
--
ALTER TABLE `continent_team`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cup`
--
ALTER TABLE `cup`
  MODIFY `CupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cup_schdule`
--
ALTER TABLE `cup_schdule`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cup_team`
--
ALTER TABLE `cup_team`
  MODIFY `Cup_TeamID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `match_cards`
--
ALTER TABLE `match_cards`
  MODIFY `MatchCardID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `match_goal`
--
ALTER TABLE `match_goal`
  MODIFY `MatchGoalID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `player`
--
ALTER TABLE `player`
  MODIFY `PlayerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `stadium`
--
ALTER TABLE `stadium`
  MODIFY `StadiumID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `TeamID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `continent_cup`
--
ALTER TABLE `continent_cup`
  ADD CONSTRAINT `continent3` FOREIGN KEY (`CupID`) REFERENCES `cup` (`CupID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `continent4` FOREIGN KEY (`Con_ID`) REFERENCES `continent` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `continent_team`
--
ALTER TABLE `continent_team`
  ADD CONSTRAINT `continent1` FOREIGN KEY (`T_ID`) REFERENCES `team` (`TeamID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `continent2` FOREIGN KEY (`C_ID`) REFERENCES `continent` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cup_schdule`
--
ALTER TABLE `cup_schdule`
  ADD CONSTRAINT `cup_schdule1` FOREIGN KEY (`CupID`) REFERENCES `cup` (`CupID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cupteam1` FOREIGN KEY (`Team1ID`) REFERENCES `team` (`TeamID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cupteam2` FOREIGN KEY (`Team2ID`) REFERENCES `team` (`TeamID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stadium1` FOREIGN KEY (`StadiumID`) REFERENCES `stadium` (`StadiumID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cup_team`
--
ALTER TABLE `cup_team`
  ADD CONSTRAINT `cup_2` FOREIGN KEY (`CupID`) REFERENCES `cup` (`CupID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teamcup` FOREIGN KEY (`TeamID`) REFERENCES `team` (`TeamID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `match_cards`
--
ALTER TABLE `match_cards`
  ADD CONSTRAINT `cardmatch_1` FOREIGN KEY (`MatchID`) REFERENCES `cup_schdule` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `matchcard` FOREIGN KEY (`CardID`) REFERENCES `card_type` (`CardID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `playercard` FOREIGN KEY (`PlayerID`) REFERENCES `player` (`PlayerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `match_goal`
--
ALTER TABLE `match_goal`
  ADD CONSTRAINT `matchgoal1` FOREIGN KEY (`MatchID`) REFERENCES `cup_schdule` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `matchplayer` FOREIGN KEY (`PlayerID`) REFERENCES `player` (`PlayerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `playergoal1` FOREIGN KEY (`PlayerID`) REFERENCES `player` (`PlayerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `player_1` FOREIGN KEY (`TeamID`) REFERENCES `team` (`TeamID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `coahc_1` FOREIGN KEY (`CoachID`) REFERENCES `coach` (`CoachID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
