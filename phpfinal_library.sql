-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost:3306
-- 產生時間： 2024 年 06 月 15 日 12:02
-- 伺服器版本： 10.5.20-MariaDB
-- PHP 版本： 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `id22309649_root`
--

-- --------------------------------------------------------

--
-- 資料表結構 `author`
--

CREATE TABLE `author` (
  `authorId` int(11) NOT NULL,
  `authorname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorBirthday` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `author`
--

INSERT INTO `author` (`authorId`, `authorname`, `authorBirthday`) VALUES
(1, '林建宏', '2003-10-23 16:00:00'),
(2, '馬寬宇', NULL),
(7, '余郡恩', '2004-08-06 16:00:00'),
(11, '張忠謀', NULL),
(12, '丁特', NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `book`
--

CREATE TABLE `book` (
  `bookid` int(11) NOT NULL,
  `bookname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(50) NOT NULL,
  `catId` int(11) DEFAULT NULL,
  `authorId` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `borrowdate` date DEFAULT NULL,
  `returndate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `book`
--

INSERT INTO `book` (`bookid`, `bookname`, `image`, `catId`, `authorId`, `status`, `borrowdate`, `returndate`) VALUES
(55, 'PHP8', '../uploads/F1473.jpg', 2, 1, 0, NULL, NULL),
(56, '打造戀愛腦', '../uploads/545454.jpg', 4, 7, 1, '2024-06-13', '2024-07-13'),
(57, '致富心態', '../uploads/88888.jpg', 3, 2, 0, NULL, NULL),
(58, '張忠謀自傳', '../uploads/BCB432A.png', 1, 11, 1, '2024-06-13', '2024-07-13'),
(59, '哈利波特-被詛咒的孩子', '../uploads/fde5h87_460x580.jpg', 5, 1, 0, NULL, NULL),
(60, '富蘭克林自傳', '../uploads/mlmmtmm_460x580.jpg', 1, 2, 0, NULL, NULL),
(61, '神鵰俠侶', '../uploads/N4C9fljwDTCpADWlgNbsSw.jpg', 5, 2, 0, NULL, NULL),
(62, '特級玩家', '../uploads/dinter.jpg', 1, 12, 1, '2024-06-13', '2024-07-13');

-- --------------------------------------------------------

--
-- 資料表結構 `borrow`
--

CREATE TABLE `borrow` (
  `borrowId` int(11) NOT NULL,
  `bookId` int(11) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  `borrowdate` date DEFAULT NULL,
  `returndate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `borrow`
--

INSERT INTO `borrow` (`borrowId`, `bookId`, `id`, `borrowdate`, `returndate`) VALUES
(22, 41, 3, '2024-06-12', NULL),
(23, 42, 3, '2024-06-12', '2024-06-12'),
(24, 43, 2, '2024-06-12', '2024-06-12'),
(25, 43, 2, '2024-06-12', '2024-06-12'),
(26, 49, 2, '2024-06-12', '2024-06-12'),
(27, 44, 2, '2024-06-12', NULL),
(28, 43, 2, '2024-06-12', '2024-06-12'),
(29, 45, 2, '2024-06-12', NULL),
(30, 42, 1, '2024-06-12', '2024-06-12'),
(31, 43, 1, '2024-06-12', NULL),
(32, 46, 1, '2024-06-12', NULL),
(33, 52, 1, '2024-06-12', NULL),
(34, 57, 3, '2024-06-13', '2024-06-13'),
(35, 55, 3, '2024-06-13', '2024-06-13'),
(36, 55, 3, '2024-06-13', '2024-06-13'),
(37, 55, 3, '2024-06-13', '2024-06-13'),
(38, 57, 3, '2024-06-13', '2024-06-13'),
(39, 57, 3, '2024-06-13', '2024-06-13'),
(40, 56, 3, '2024-06-13', '2024-06-13'),
(41, 56, 3, '2024-06-13', '2024-06-13'),
(42, 56, 3, '2024-06-13', '2024-06-13'),
(43, 55, 1, '2024-06-13', '2024-06-13'),
(44, 62, 1, '2024-06-13', '2024-06-13'),
(45, 62, 1, '2024-06-13', '2024-06-13'),
(46, 59, 3, '2024-06-13', '2024-06-13'),
(47, 62, 3, '2024-06-13', NULL),
(48, 58, 6, '2024-06-13', NULL),
(49, 55, 6, '2024-06-13', '2024-06-13'),
(50, 56, 6, '2024-06-13', NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `category`
--

CREATE TABLE `category` (
  `catId` int(11) NOT NULL,
  `catname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `category`
--

INSERT INTO `category` (`catId`, `catname`) VALUES
(1, '自傳'),
(2, '程式'),
(3, '財金'),
(4, '愛情'),
(5, '小說'),
(9, '12');

-- --------------------------------------------------------

--
-- 資料表結構 `stuff`
--

CREATE TABLE `stuff` (
  `id` int(11) NOT NULL,
  `stuffname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `stuff`
--

INSERT INTO `stuff` (`id`, `stuffname`, `password`) VALUES
(1, 'stuff1', '123'),
(2, 'stuff2', '123');

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`id`, `name`, `username`, `password`, `email`, `phone`) VALUES
(1, '馬寬宇', 'kauyuma', '0603', 'a1113301@mail.nuk.edu.tw', '123456789'),
(2, '余郡恩', 'eric', '87', 'ericyu0807@gmail.com', '0926512588'),
(3, '林建宏', 'homer', '123456', 'a1113329@mail.nuk.edu.tw', '94651'),
(4, '丁一賢', 'Ding', 'TT', 'TT123', '123'),
(5, '丁一賢', 'DingDing', '12121212', 'TT123', '1093456846451'),
(6, '丁丁帥', 'dingdingH', 'dingdingH', 'a1113362@mail.nuk.edu.tw', '0912345678');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`authorId`);

--
-- 資料表索引 `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`bookid`);

--
-- 資料表索引 `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`borrowId`);

--
-- 資料表索引 `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`catId`);

--
-- 資料表索引 `stuff`
--
ALTER TABLE `stuff`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `author`
--
ALTER TABLE `author`
  MODIFY `authorId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `book`
--
ALTER TABLE `book`
  MODIFY `bookid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `borrow`
--
ALTER TABLE `borrow`
  MODIFY `borrowId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `category`
--
ALTER TABLE `category`
  MODIFY `catId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `stuff`
--
ALTER TABLE `stuff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
