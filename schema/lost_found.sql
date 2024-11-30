-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 07:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lost_found`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(6, 'Bags'),
(7, 'Books and Notebooks'),
(4, 'Cash'),
(2, 'Clothing'),
(3, 'Documents'),
(1, 'Electronics'),
(5, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_date` datetime NOT NULL,
  `comment_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `comment_date`, `comment_text`) VALUES
(1, 1, 1, '2024-11-24 20:20:55', 'I forgot to add, I have two notebooks, few pens and a lenovo laptop charger in my bag.'),
(4, 3, 2, '2024-11-24 21:01:42', 'Is it still lost?'),
(5, 3, 1, '2024-11-25 07:00:03', 'Hello'),
(6, 3, 3, '2024-11-25 10:52:40', 'Have you found your phone?'),
(7, 4, 5, '2024-11-27 09:30:30', 'The hoodie has been returned to its owner.');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `post_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location_reported` varchar(255) DEFAULT NULL,
  `date_reported` date DEFAULT NULL,
  `item_description` text DEFAULT NULL,
  `post_type` enum('Lost','Found') NOT NULL,
  `item_status` enum('Pending','Resolved','Closed') NOT NULL DEFAULT 'Pending',
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`post_id`, `title`, `location_reported`, `date_reported`, `item_description`, `post_type`, `item_status`, `user_id`, `category_id`, `created_at`) VALUES
(1, 'Black Bagpack', 'Shaheed Abdur Rab Hall Playground', '2024-11-23', 'A Black Arctice Hunter Bagpack was lost near the Shaheed Abdur Rab Hall Playground. ', 'Lost', 'Pending', 1, 6, '2024-11-24 14:19:47'),
(3, 'Samsung Smartphone', 'Zero Point, CU', '2024-11-19', 'Samsung Galaxy S24 Black Color', 'Lost', 'Pending', 2, 1, '2024-11-24 15:00:45'),
(4, 'Blue Hoodie', 'BBA Cafeteria', '2024-11-25', 'A blue hoodie(Size-L) was found at BBA Cafeteria.', 'Found', 'Resolved', 5, 2, '2024-11-26 03:40:54'),
(5, 'CSEPL-24 Jersey', 'Shaheed Abdur Rab Hall', '2024-11-26', 'I lost My CSEPL Jersey from premises of Shaheed Abdur Rab Hall.', 'Lost', 'Resolved', 1, 2, '2024-11-27 02:28:02'),
(8, 'Fresh Spiral Notebook', 'Biology Faculty', '2024-11-28', 'A notebook was lost on the premises of biology faculty. The cover of the notebook has a picture of Eiffel Tower.', 'Lost', 'Pending', 8, 7, '2024-11-29 10:49:53');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `email`, `phone_number`, `password`) VALUES
(1, 'Md. Emranul Hoque', 'emran.cse.cu@gmail.com', '01881726226', '$2y$10$Mos1s50vcIigzA5h2MMdeukf38.fgYjOg/MlauqYBcXLwOb2oMche'),
(2, 'Pranto Chandra Das', '9ihateyou9@gmail.com', '01609302008', '$2y$10$CUd/KnntKeU8Ebup/MBpueNnSJBBpPz8tfSjm8Uo5vz60QWfeafHm'),
(3, 'Adnan Uddin', 'adnanuddincse.cu@gmail.com', '01822238016', '$2y$10$3KpGMW3oTvP/jEkoVIMzBe/eZ.Rlf0ydn7dZGX7pfiupqozmX6chO'),
(4, 'Abu Ruhan Mahamud', 'ruhan.csecu@gmail.com', '01634654267', '$2y$10$YsP1FTSmZ0Rj/dbkHA4QCuENDZSm2aKkzNvtYTs0pjlP8RMyjOSQi'),
(5, 'Sayeed Mahdi', 'sayeedmahdi.csecu@gmail.com', '01620170707', '$2y$10$F2bxm72O266An3zdljIm/eYG5sD3ZHcYeSoqK0w02YXOwVm8FXC9y'),
(6, 'Arafat Sheikh', 'arafat.csecu@gmail.com', '01818555444', '$2y$10$hYTRdcGPvQO66Pf3jPvqleT5ukxJYG0OzaomN2zA0mwfCiZAVLQLa'),
(7, 'Ahsanur Rahman', 'ahsanur.rahman@gmail.com', '01688700007', '$2y$10$FTHHyBCF/Dluk4Motnl10uiKzLnXn0ZPEdGHWHSncid71Qd1RAiNK'),
(8, 'Misbah Ul Islam', 'misbah.bmb@gmail.com', '01883872322', '$2y$10$so5AdtKXilW/xhlgT.jcreU9m2E.ZJUxiobVkZRsx2SXxaG7nzZry');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `comments_ibfk_1` (`post_id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
