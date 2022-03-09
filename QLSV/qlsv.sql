-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 09, 2022 lúc 05:57 AM
-- Phiên bản máy phục vụ: 10.4.18-MariaDB
-- Phiên bản PHP: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qlsv`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `challenge`
--

CREATE TABLE `challenge` (
  `id` int(11) NOT NULL,
  `teacherId` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filePath` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `modified_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `challenge`
--

INSERT INTO `challenge` (`id`, `teacherId`, `title`, `description`, `filePath`, `modified_time`) VALUES
(6, 1, 'dap an chua biet', '111', '../uploads/dapan.txt', '2022-03-08 14:05:22');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `exercise`
--

CREATE TABLE `exercise` (
  `id` int(11) NOT NULL,
  `teacherId` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filePath` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `modified_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `exercise`
--

INSERT INTO `exercise` (`id`, `teacherId`, `title`, `description`, `filePath`, `modified_time`) VALUES
(21, 1, 'bai 1', '123', '../uploads/challenge11.txt', '2022-03-08 13:45:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `sendId` int(11) NOT NULL,
  `receiveId` int(11) NOT NULL,
  `content` text NOT NULL,
  `sendTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `message`
--

INSERT INTO `message` (`id`, `sendId`, `receiveId`, `content`, `sendTime`) VALUES
(20, 1, 2, 'hello', '2022-03-07 22:58:12'),
(21, 1, 2, 'chao', '2022-03-07 22:58:23'),
(26, 1, 2, 'qq', '2022-03-07 23:05:11'),
(27, 1, 7, '111', '2022-03-07 23:05:23'),
(28, 1, 2, 'chao', '2022-03-08 13:02:46'),
(30, 11, 1, 'chao ban', '2022-03-08 21:55:02'),
(31, 11, 7, 'hello', '2022-03-08 22:04:59'),
(32, 11, 7, 'hellodd', '2022-03-08 22:14:37');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sbmexercise`
--

CREATE TABLE `sbmexercise` (
  `id` int(11) NOT NULL,
  `exerciseId` int(11) NOT NULL,
  `studentId` int(11) NOT NULL,
  `filePath` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `sbm_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sbmexercise`
--

INSERT INTO `sbmexercise` (`id`, `exerciseId`, `studentId`, `filePath`, `sbm_time`) VALUES
(5, 21, 11, '../uploads/dapan2.txt', '2022-03-08 20:42:04');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phoneNumber` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` enum('student','teacher','admin') COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `fullname`, `email`, `phoneNumber`, `type`, `avatar`) VALUES
(1, 'teacher1', '123456a@A', 'Giáo viên 1', 'teacher@admin.com', '012345678', 'teacher', 'ANHDEP1.png'),
(2, 'teacher2', '123456a@A', 'Giáo viên 2', 'trang@gmail.com', '0258741369', 'teacher', 'ANHDEP.png'),
(7, 'student2', '123456a@A', 'Học sinh 2', 'hocsinh2@gmail.com', '0123456789', 'student', 'ANHDEP3.png'),
(11, 'student1', '123456a@A', 'Học sinh 1', 'hocsinh11@gmail.com', '123456789', 'student', 'ANHDEP.png');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `challenge`
--
ALTER TABLE `challenge`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacherId` (`teacherId`);

--
-- Chỉ mục cho bảng `exercise`
--
ALTER TABLE `exercise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacherId` (`teacherId`);

--
-- Chỉ mục cho bảng `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sendId` (`sendId`),
  ADD KEY `receiveId` (`receiveId`);

--
-- Chỉ mục cho bảng `sbmexercise`
--
ALTER TABLE `sbmexercise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exerciseId` (`exerciseId`),
  ADD KEY `studentId` (`studentId`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `challenge`
--
ALTER TABLE `challenge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `exercise`
--
ALTER TABLE `exercise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `sbmexercise`
--
ALTER TABLE `sbmexercise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `challenge`
--
ALTER TABLE `challenge`
  ADD CONSTRAINT `challenge_ibfk_1` FOREIGN KEY (`teacherId`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `exercise`
--
ALTER TABLE `exercise`
  ADD CONSTRAINT `exercise_ibfk_1` FOREIGN KEY (`teacherId`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`sendId`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`receiveId`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `sbmexercise`
--
ALTER TABLE `sbmexercise`
  ADD CONSTRAINT `sbmExercise_ibfk_1` FOREIGN KEY (`exerciseId`) REFERENCES `exercise` (`id`),
  ADD CONSTRAINT `sbmExercise_ibfk_2` FOREIGN KEY (`studentId`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
