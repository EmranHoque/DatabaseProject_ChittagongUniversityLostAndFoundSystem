-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 06:13 AM
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
(9, 'Accessories'),
(6, 'Bags'),
(7, 'Books and Notebooks'),
(2, 'Clothing'),
(3, 'Documents'),
(1, 'Electronics'),
(8, 'Jewelry'),
(5, 'Others'),
(4, 'Wallets');

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
(10, 9, 1, '2024-12-05 21:25:55', 'ab'),
(11, 52, 4, '2024-12-07 11:09:41', '.');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `post_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location_reported` enum('1 No. Gate Area','2 No. Gate Area','Zero Point','Shaheed Minar','Central Library','Gymnasium','Botanical Garden','Chittagong University Medical Center','Jamal Nazrul Islam Research Centre','Institute of Forestry and Environmental Sciences','Faculty of Engineering','Faculty of Science','Faculty of Biological Science','Institute of Marine Science','Faculty of Social Sciences','Faculty of Business Administration','Faculty of Law','Alaol Hall','A. F. Rahman Hall','Shahjalal Hall','Suhrawardy Hall','Shah Amanat Hall','Shamsun Nahar Hall','Shaheed Abdur Rab Hall','Pritilata Hall','Deshnetri Begum Khaleda Zia Hall','Masterda Suriya Sen Hall','Bangabandhu Sheikh Mujibur Rahman Hall','Janonetri Sheikh Hasina Hall','Bangamata Sheikh Fazilatunnesa Mujib Hall','Artist Rashid Chowdhury Hostel','Atish Dipangkar Srigyan Hall','Chittagong University School & College') DEFAULT NULL,
  `date_reported` date DEFAULT NULL,
  `item_description` text DEFAULT NULL,
  `post_type` enum('Lost','Found') NOT NULL,
  `item_status` enum('Pending','Resolved','Closed') NOT NULL DEFAULT 'Pending',
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`post_id`, `title`, `location_reported`, `date_reported`, `item_description`, `post_type`, `item_status`, `user_id`, `category_id`, `created_at`, `image_path`) VALUES
(1, 'Black Backpack', 'Shaheed Abdur Rab Hall', '2024-11-23', 'A Black Arctic Hunter Backpack was lost near the Shaheed Abdur Rab Hall Playground.', 'Lost', 'Resolved', 1, 6, '2024-11-24 08:19:47', NULL),
(2, 'Black Wallet', 'Zero Point', '2024-11-23', 'A black leather wallet containing credit cards and ID.', 'Lost', 'Resolved', 1, 4, '2024-12-03 10:45:14', NULL),
(3, 'Samsung Smartphone', 'Zero Point', '2024-11-19', 'Samsung Galaxy S24 Black Color.', 'Lost', 'Resolved', 2, 1, '2024-11-24 09:00:45', NULL),
(4, 'Blue Hoodie', 'Faculty of Business Administration', '2024-11-25', 'A blue hoodie (Size-L) was found at the BBA Cafeteria.', 'Found', 'Resolved', 5, 2, '2024-11-25 21:40:54', NULL),
(5, 'CSEPL-24 Jersey', 'Shaheed Abdur Rab Hall', '2024-11-26', 'I lost my CSEPL Jersey from the premises of Shaheed Abdur Rab Hall.', 'Lost', 'Resolved', 1, 2, '2024-11-26 20:28:02', NULL),
(6, 'Red Keychain', 'Shaheed Abdur Rab Hall', '2024-11-22', 'A bunch of keys with a red keychain.', 'Found', 'Resolved', 2, 6, '2024-12-03 10:45:14', NULL),
(7, 'Dell Laptop', 'Faculty of Business Administration', '2024-11-21', 'Dell laptop with a sticker of Marvel.', 'Lost', 'Resolved', 3, 1, '2024-12-03 10:45:14', NULL),
(8, 'Fresh Spiral Notebook', 'Faculty of Biological Science', '2024-11-28', 'A notebook lost on the premises of the biology faculty. The cover has a picture of the Eiffel Tower.', 'Lost', 'Resolved', 8, 7, '2024-11-29 04:49:53', NULL),
(9, 'Spiral Notebook', 'Faculty of Biological Science', '2024-11-20', 'A spiral notebook with notes about cell biology.', 'Found', 'Resolved', 4, 7, '2024-12-03 10:45:14', NULL),
(10, 'Cricket Bat', 'Gymnasium', '2024-11-30', 'A cricket bat was found at the Gymnasium.', 'Found', 'Pending', 6, 5, '2024-11-30 08:28:15', NULL),
(11, 'Silver Ring', 'Shaheed Minar', '2024-12-01', 'A silver ring with initials engraved was found near Shaheed Minar.', 'Found', 'Resolved', 7, 3, '2024-12-02 05:15:30', NULL),
(12, 'Blue Water Bottle', 'Central Library', '2024-11-29', 'A blue water bottle with a flip cap was left in the reading room.', 'Lost', 'Pending', 2, 5, '2024-12-01 03:42:17', NULL),
(13, 'Leather Wallet', 'Shaheed Minar', '2024-11-30', 'A brown leather wallet with cash and ID cards.', 'Lost', 'Resolved', 4, 4, '2024-12-02 08:20:55', NULL),
(14, 'Bike Helmet', 'Faculty of Engineering', '2024-12-01', 'A black helmet left near the parking area.', 'Lost', 'Pending', 3, 6, '2024-12-01 10:50:45', NULL),
(15, 'Black Umbrella', 'Faculty of Law', '2024-11-28', 'A black umbrella was found in the hallway.', 'Found', 'Resolved', 5, 2, '2024-11-29 06:30:20', NULL),
(16, 'Handbag', 'Zero Point', '2024-12-02', 'A red handbag containing documents and personal items.', 'Lost', 'Pending', 6, 6, '2024-12-03 02:22:10', NULL),
(17, 'Gold Necklace', 'A. F. Rahman Hall', '2024-11-27', 'A gold necklace found near the dorm entrance.', 'Found', 'Resolved', 7, 8, '2024-11-28 07:10:40', NULL),
(18, 'Leather Belt', 'Botanical Garden', '2024-12-01', 'A brown leather belt with a custom buckle.', 'Found', 'Pending', 8, 5, '2024-12-01 12:15:00', NULL),
(19, 'Power Bank', 'Chittagong University Medical Center', '2024-12-01', 'A white power bank was left in the waiting area.', 'Lost', 'Pending', 2, 1, '2024-12-02 04:30:15', NULL),
(20, 'Red Jacket', 'Shaheed Abdur Rab Hall', '2024-11-26', 'A red jacket with a hood lost near the canteen.', 'Lost', 'Resolved', 9, 7, '2024-11-27 08:25:35', NULL),
(21, 'Bluetooth Speaker', 'Gymnasium', '2024-11-28', 'A black portable Bluetooth speaker.', 'Lost', 'Resolved', 5, 9, '2024-12-01 05:05:50', NULL),
(22, 'Calculator', 'Faculty of Science', '2024-11-30', 'A Casio FX-991EX scientific calculator.', 'Lost', 'Resolved', 1, 5, '2024-12-01 06:45:20', NULL),
(23, 'Passport', 'Zero Point', '2024-11-27', 'A passport belonging to a foreign national.', 'Found', 'Resolved', 2, 4, '2024-11-28 03:50:40', NULL),
(24, 'ID Card', 'Faculty of Social Sciences', '2024-12-01', 'An ID card belonging to a student from the history department.', 'Found', 'Pending', 3, 1, '2024-12-02 02:15:30', NULL),
(25, 'Green Notebook', 'Faculty of Business Administration', '2024-11-29', 'A notebook with notes on financial management.', 'Lost', 'Resolved', 7, 7, '2024-11-30 08:25:10', NULL),
(26, 'Wrist Watch', 'Faculty of Biological Science', '2024-11-30', 'A silver analog wristwatch.', 'Lost', 'Pending', 6, 2, '2024-12-01 10:30:40', NULL),
(27, 'Earphones', 'Institute of Marine Science', '2024-11-29', 'Black wireless earphones in a case.', 'Lost', 'Resolved', 5, 8, '2024-12-01 07:10:00', NULL),
(28, 'Mathematics Textbook', 'Faculty of Engineering', '2024-11-30', 'A mathematics textbook with the owner’s name written on the cover.', 'Lost', 'Resolved', 3, 6, '2024-12-02 09:50:30', NULL),
(29, 'Set of Keys', 'Shaheed Minar', '2024-12-01', 'A set of keys with a small red tag.', 'Found', 'Pending', 9, 6, '2024-12-02 04:15:45', NULL),
(30, 'Cricket Bat', 'Alaol Hall', NULL, 'A cricket ball was left during a match.', 'Found', 'Resolved', 1, 9, '2024-11-29 11:20:50', 'uploads/1733458270_ccc-500x500.jpg'),
(31, 'Laptop Charger', 'Faculty of Social Sciences', '2024-11-30', 'A laptop charger for a Dell laptop.', 'Lost', 'Resolved', 4, 1, '2024-12-01 03:15:40', NULL),
(32, 'Orange Backpack', 'Shaheed Abdur Rab Hall', '2024-11-29', 'An orange backpack containing a laptop and books.', 'Lost', 'Resolved', 2, 5, '2024-12-01 05:35:10', NULL),
(33, 'Gold Bracelet', 'Shaheed Minar', '2024-11-27', 'A gold bracelet with a floral design.', 'Found', 'Resolved', 3, 8, '2024-11-28 04:10:20', NULL),
(34, 'Black Sunglasses', '1 No. Gate Area', '2024-12-01', 'Black sunglasses in a leather case.', 'Lost', 'Pending', 6, 3, '2024-12-02 06:30:50', NULL),
(35, 'Digital Camera', 'Botanical Garden', '2024-12-01', 'A Canon digital camera with a memory card.', 'Lost', 'Resolved', 8, 7, '2024-12-02 09:40:10', NULL),
(36, 'Keychain with Pendrive', 'Faculty of Law', '2024-11-30', 'A blue keychain with a USB pendrive attached.', 'Found', 'Resolved', 7, 2, '2024-12-01 03:30:50', NULL),
(37, 'Shoes', 'Institute of Forestry and Environmental Sciences', '2024-11-28', 'A pair of black shoes left at the building entrance.', 'Found', 'Pending', 5, 4, '2024-11-29 07:15:30', NULL),
(38, 'Sports Jersey', 'Faculty of Law', '2024-11-27', 'A yellow sports jersey with the number 23.', 'Lost', 'Resolved', 3, 6, '2024-11-28 08:50:30', NULL),
(39, 'Green Water Bottle', 'Faculty of Science', '2024-12-01', 'A green metal water bottle with a dent.', 'Lost', 'Pending', 9, 5, '2024-12-02 07:20:45', NULL),
(40, 'Wallet with Cash', 'Shaheed Minar', '2024-11-30', 'A brown wallet containing a significant amount of cash.', 'Lost', 'Resolved', 2, 3, '2024-12-01 10:25:10', NULL),
(41, 'Mobile Phone Cover', 'Shaheed Abdur Rab Hall', '2024-11-29', 'A red mobile phone cover with a card holder.', 'Found', 'Pending', 6, 7, '2024-11-30 09:45:20', NULL),
(42, 'Grey Hoodie', 'Chittagong University Medical Center', '2024-12-01', 'A grey hoodie left in the waiting area.', 'Lost', 'Resolved', 8, 2, '2024-12-02 08:30:35', NULL),
(43, 'Textbook on Economics', 'Faculty of Business Administration', '2024-11-29', 'A textbook on microeconomics with highlighted notes.', 'Lost', 'Resolved', 3, 6, '2024-11-30 12:50:20', NULL),
(52, 'Casio 991EX Calculator', 'Institute of Marine Science', '2024-12-05', 'A Casio FX-991EX Calculator was lost at around 2.30 P.M. ', 'Lost', 'Pending', 1, 9, '2024-12-06 03:19:31', 'uploads/img_67526d435c3ec2.35626836.jpg');

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
(8, 'Misbah Ul Islam', 'misbah.bmb@gmail.com', '01883872322', '$2y$10$so5AdtKXilW/xhlgT.jcreU9m2E.ZJUxiobVkZRsx2SXxaG7nzZry'),
(9, 'Sanzid Islam', 'sanzid.csecu@gmail.com', '01871114187', '$2y$10$eATqKk10cPEdW2VsG/4aEuteKC15cVLsVJPEDJCVyBne6vC94eIZy');

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
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
