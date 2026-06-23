-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 01, 2026 at 04:11 AM
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
-- Database: `Quizzer Database`
--

-- --------------------------------------------------------

--
-- Table structure for table `Course`
--

CREATE TABLE `Course` (
  `id` int(11) NOT NULL,
  `coursename` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Course`
--

INSERT INTO `Course` (`id`, `coursename`) VALUES
(1, 'Database Systems'),
(2, 'Operating Systems'),
(3, 'Probability and Statistics');

-- --------------------------------------------------------

--
-- Table structure for table `Options`
--

CREATE TABLE `Options` (
  `id` int(11) NOT NULL,
  `q_id` int(11) DEFAULT NULL,
  `text` varchar(200) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Options`
--

INSERT INTO `Options` (`id`, `q_id`, `text`, `is_correct`) VALUES
(1, 1, 'Structured Query Language', 1),
(2, 1, 'Simple Query Language', 0),
(3, 1, 'Structured Question Language', 0),
(4, 1, 'Standard Query Logic', 0),
(5, 2, 'Foreign Key', 0),
(6, 2, 'Primary Key', 1),
(7, 2, 'Composite Key', 0),
(8, 2, 'Super Key', 0),
(9, 3, 'Data is always consistent', 0),
(10, 3, 'Transactions are completely isolated', 0),
(11, 3, 'All or nothing execution', 1),
(12, 3, 'Data is durable', 0),
(13, 4, '1NF', 0),
(14, 4, '2NF', 1),
(15, 4, '3NF', 0),
(16, 4, 'BCNF', 0),
(17, 5, 'DELETE', 0),
(18, 5, 'TRUNCATE', 0),
(19, 5, 'DROP', 1),
(20, 5, 'REMOVE', 0),
(21, 6, 'INNER JOIN', 0),
(22, 6, 'RIGHT JOIN', 0),
(23, 6, 'LEFT JOIN', 1),
(24, 6, 'FULL OUTER JOIN', 0),
(25, 7, 'Trigger', 0),
(26, 7, 'Stored Procedure', 0),
(27, 7, 'Index', 0),
(28, 7, 'View', 1),
(29, 8, 'UNIQUE', 0),
(30, 8, 'NOT NULL', 1),
(31, 8, 'CHECK', 0),
(32, 8, 'DEFAULT', 0),
(33, 9, 'Index', 1),
(34, 9, 'Trigger', 0),
(35, 9, 'Foreign Key', 0),
(36, 9, 'Cursor', 0),
(37, 10, 'Attribute', 0),
(38, 10, 'Relationship', 0),
(39, 10, 'Entity', 1),
(40, 10, 'Key', 0),
(41, 11, 'Shell', 0),
(42, 11, 'Kernel', 1),
(43, 11, 'Compiler', 0),
(44, 11, 'GUI', 0),
(45, 12, 'Round Robin', 0),
(46, 12, 'SJF', 0),
(47, 12, 'FCFS', 1),
(48, 12, 'Priority Scheduling', 0),
(49, 13, 'Starvation', 0),
(50, 13, 'Deadlock', 1),
(51, 13, 'Thrashing', 0),
(52, 13, 'Race Condition', 0),
(53, 14, 'Mutual Exclusion', 0),
(54, 14, 'Hold and Wait', 0),
(55, 14, 'Preemption', 1),
(56, 14, 'Circular Wait', 0),
(57, 15, 'Paging', 0),
(58, 15, 'Swapping', 1),
(59, 15, 'Segmentation', 0),
(60, 15, 'Fragmentation', 0),
(61, 16, 'Thrashing', 1),
(62, 16, 'Starvation', 0),
(63, 16, 'Demand Paging', 0),
(64, 16, 'Caching', 0),
(65, 17, 'Semaphore', 1),
(66, 17, 'Thread', 0),
(67, 17, 'Socket', 0),
(68, 17, 'Pipe', 0),
(69, 18, 'Thread Switch', 0),
(70, 18, 'Context Switch', 1),
(71, 18, 'Mode Switch', 0),
(72, 18, 'State Transition', 0),
(73, 19, 'Frames', 0),
(74, 19, 'Pages', 1),
(75, 19, 'Segments', 0),
(76, 19, 'Sectors', 0),
(77, 20, 'ALU', 0),
(78, 20, 'MMU', 1),
(79, 20, 'CPU', 0),
(80, 20, 'Register', 0),
(81, 21, '0', 0),
(82, 21, '0.5', 0),
(83, 21, '1', 1),
(84, 21, 'Infinity', 0),
(85, 22, 'Mean', 0),
(86, 22, 'Median', 1),
(87, 22, 'Mode', 0),
(88, 22, 'Range', 0),
(89, 23, 'Mean Deviation', 0),
(90, 23, 'Standard Deviation', 1),
(91, 23, 'Covariance', 0),
(92, 23, 'Correlation', 0),
(93, 24, 'Independent', 0),
(94, 24, 'Dependent', 0),
(95, 24, 'Mutually Exclusive', 1),
(96, 24, 'Equally Likely', 0),
(97, 25, 'U-shaped', 0),
(98, 25, 'J-shaped', 0),
(99, 25, 'Bell-shaped', 1),
(100, 25, 'Linear', 0),
(101, 26, 'Type I error', 1),
(102, 26, 'Type II error', 0),
(103, 26, 'Correct decision', 0),
(104, 26, 'Power of the test', 0),
(105, 27, 'Pythagoras Theorem', 0),
(106, 27, 'Binomial Theorem', 0),
(107, 27, 'Bayes Theorem', 1),
(108, 27, 'Central Limit Theorem', 0),
(109, 28, 'Combination', 0),
(110, 28, 'Permutation', 1),
(111, 28, 'Sample', 0),
(112, 28, 'Subset', 0),
(113, 29, 'Binomial', 0),
(114, 29, 'Normal', 0),
(115, 29, 'Poisson', 1),
(116, 29, 'Exponential', 0),
(117, 30, 'Parameter', 0),
(118, 30, 'Statistic', 0),
(119, 30, 'Sample', 1),
(120, 30, 'Variable', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Question`
--

CREATE TABLE `Question` (
  `id` int(11) NOT NULL,
  `CourseID` int(11) DEFAULT NULL,
  `text` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Question`
--

INSERT INTO `Question` (`id`, `CourseID`, `text`) VALUES
(1, 1, 'What does SQL stand for?'),
(2, 1, 'Which of the following uniquely identifies a row in a table?'),
(3, 1, 'What does the ACID property \"Atomicity\" mean?'),
(4, 1, 'Which normal form eliminates partial dependencies?'),
(5, 1, 'What command is used to remove a table structure entirely?'),
(6, 1, 'Which join returns all rows from the left table, and matching rows from the right?'),
(7, 1, 'What is a virtual table created by a query called?'),
(8, 1, 'Which constraint ensures a column cannot have a NULL value?'),
(9, 1, 'What is primarily used to speed up data retrieval?'),
(10, 1, 'In an ER diagram, what does a rectangle represent?'),
(11, 2, 'What is the core of the operating system called?'),
(12, 2, 'Which scheduling algorithm allocates the CPU first to the process that requests it first?'),
(13, 2, 'What is a condition where two or more processes are waiting indefinitely for an event?'),
(14, 2, 'Which of these is NOT a Coffman condition for deadlock?'),
(15, 2, 'The process of swapping a process out to a backing store and then bringing it back is called:'),
(16, 2, 'What is it called when a system spends more time paging than executing?'),
(17, 2, 'Which tool is used for process synchronization?'),
(18, 2, 'Saving the state of an old process and loading the saved state of a new process is a:'),
(19, 2, 'Logical memory is broken into blocks of the same size called:'),
(20, 2, 'Which component maps logical addresses to physical addresses?'),
(21, 3, 'What is the sum of probabilities of all possible outcomes in a sample space?'),
(22, 3, 'Which measure of central tendency is the middle value of a sorted dataset?'),
(23, 3, 'What is the square root of Variance?'),
(24, 3, 'If events A and B cannot occur simultaneously, they are:'),
(25, 3, 'What shape is a Normal Distribution curve?'),
(26, 3, 'Rejecting a true null hypothesis is a:'),
(27, 3, 'Which theorem helps calculate conditional probability?'),
(28, 3, 'An arrangement of objects where order matters is called a:'),
(29, 3, 'Which distribution describes the number of events in a fixed time interval?'),
(30, 3, 'What is a subset of a population called?');

-- --------------------------------------------------------

--
-- Table structure for table `QuizAttempt`
--

CREATE TABLE `QuizAttempt` (
  `id` int(11) NOT NULL,
  `u_id` int(11) DEFAULT NULL,
  `c_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `date_taken` date DEFAULT NULL,
  `time_taken` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `QuizAttempt`
--

INSERT INTO `QuizAttempt` (`id`, `u_id`, `c_id`, `score`, `date_taken`, `time_taken`) VALUES
(1, 1, 1, 9, '2026-04-17', '00:00:27'),
(2, 1, 2, 9, '2026-04-17', '00:01:56'),
(3, 1, 2, 9, '2026-04-17', '00:00:03'),
(4, 1, 1, 9, '2026-04-17', '00:00:07'),
(5, 1, 1, 9, '2026-04-17', '00:00:01'),
(6, 1, 1, 10, '2026-04-17', '00:00:35'),
(8, 3, 1, 7, '2026-04-18', '00:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `Response`
--

CREATE TABLE `Response` (
  `id` int(11) NOT NULL,
  `attempt_id` int(11) DEFAULT NULL,
  `q_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `chosen_option_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Response`
--

INSERT INTO `Response` (`id`, `attempt_id`, `q_id`, `score`, `chosen_option_id`) VALUES
(1, 1, 1, 1, 1),
(2, 1, 2, 1, 6),
(3, 1, 3, 1, 11),
(4, 1, 4, 1, 14),
(5, 1, 5, 0, 18),
(6, 1, 6, 1, 23),
(7, 1, 7, 1, 28),
(8, 1, 8, 1, 30),
(9, 1, 9, 1, 33),
(10, 1, 10, 1, 39),
(11, 2, 11, 1, 42),
(12, 2, 12, 1, 47),
(13, 2, 13, 1, 50),
(14, 2, 14, 1, 55),
(15, 2, 15, 1, 58),
(16, 2, 16, 1, 61),
(17, 2, 17, 1, 65),
(18, 2, 18, 1, 70),
(19, 2, 19, 0, 76),
(20, 2, 20, 1, 78),
(21, 3, 11, 1, 42),
(22, 3, 12, 1, 47),
(23, 3, 13, 1, 50),
(24, 3, 14, 1, 55),
(25, 3, 15, 1, 58),
(26, 3, 16, 1, 61),
(27, 3, 17, 1, 65),
(28, 3, 18, 1, 70),
(29, 3, 19, 0, 76),
(30, 3, 20, 1, 78),
(31, 4, 1, 1, 1),
(32, 4, 2, 1, 6),
(33, 4, 3, 1, 11),
(34, 4, 4, 1, 14),
(35, 4, 5, 0, 18),
(36, 4, 6, 1, 23),
(37, 4, 7, 1, 28),
(38, 4, 8, 1, 30),
(39, 4, 9, 1, 33),
(40, 4, 10, 1, 39),
(41, 5, 1, 1, 1),
(42, 5, 2, 1, 6),
(43, 5, 3, 1, 11),
(44, 5, 4, 1, 14),
(45, 5, 5, 0, 18),
(46, 5, 6, 1, 23),
(47, 5, 7, 1, 28),
(48, 5, 8, 1, 30),
(49, 5, 9, 1, 33),
(50, 5, 10, 1, 39),
(51, 6, 1, 1, 1),
(52, 6, 2, 1, 6),
(53, 6, 3, 1, 11),
(54, 6, 4, 1, 14),
(55, 6, 5, 1, 19),
(56, 6, 6, 1, 23),
(57, 6, 7, 1, 28),
(58, 6, 8, 1, 30),
(59, 6, 9, 1, 33),
(60, 6, 10, 1, 39),
(61, 8, 1, 0, 3),
(62, 8, 2, 1, 6),
(63, 8, 3, 0, 10),
(64, 8, 4, 0, 15),
(65, 8, 5, 1, 19),
(66, 8, 6, 1, 23),
(67, 8, 7, 1, 28),
(68, 8, 8, 1, 30),
(69, 8, 9, 1, 33),
(70, 8, 10, 1, 39);

-- --------------------------------------------------------

--
-- Table structure for table `system_user`
--

CREATE TABLE `system_user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_user`
--

INSERT INTO `system_user` (`id`, `username`, `password_hash`, `email`, `created_at`, `role`) VALUES
(1, 'maaz_admin', '$2y$10$Dp/EGb7GBykUIf7RXcE7Ve4Ls1FAY.kxLP3664U8QBBcehavQSXDS', 'maaz@example.com', '2026-04-17 02:09:30', 'admin'),
(3, 'abdulm_inspires', '$2y$10$RKvA1tao5Mdwq10PAGhj4uGSH3aDndNdnkIJ2V0fBjnYENpR9YfSS', NULL, '2026-04-17 19:37:26', 'student'),
(6, 'Hammad.ali', '$2y$10$0uaMTbsV98lB5ejdiWySKe020IgzEZuFelfFalu/QNHsBD8LDUa1i', NULL, '2026-04-18 14:43:20', 'teacher');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Course`
--
ALTER TABLE `Course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Options`
--
ALTER TABLE `Options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Options_ibfk_1` (`q_id`);

--
-- Indexes for table `Question`
--
ALTER TABLE `Question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Question_ibfk_1` (`CourseID`);

--
-- Indexes for table `QuizAttempt`
--
ALTER TABLE `QuizAttempt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `QuizAttempt_ibfk_1` (`u_id`),
  ADD KEY `QuizAttempt_ibfk_2` (`c_id`);

--
-- Indexes for table `Response`
--
ALTER TABLE `Response`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Response_ibfk_1` (`attempt_id`),
  ADD KEY `Response_ibfk_2` (`q_id`);

--
-- Indexes for table `system_user`
--
ALTER TABLE `system_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Course`
--
ALTER TABLE `Course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Options`
--
ALTER TABLE `Options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `Question`
--
ALTER TABLE `Question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `QuizAttempt`
--
ALTER TABLE `QuizAttempt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Response`
--
ALTER TABLE `Response`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `system_user`
--
ALTER TABLE `system_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Options`
--
ALTER TABLE `Options`
  ADD CONSTRAINT `Options_ibfk_1` FOREIGN KEY (`q_id`) REFERENCES `Question` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Question`
--
ALTER TABLE `Question`
  ADD CONSTRAINT `Question_ibfk_1` FOREIGN KEY (`CourseID`) REFERENCES `Course` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `QuizAttempt`
--
ALTER TABLE `QuizAttempt`
  ADD CONSTRAINT `QuizAttempt_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `system_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `QuizAttempt_ibfk_2` FOREIGN KEY (`c_id`) REFERENCES `Course` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Response`
--
ALTER TABLE `Response`
  ADD CONSTRAINT `Response_ibfk_1` FOREIGN KEY (`attempt_id`) REFERENCES `QuizAttempt` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Response_ibfk_2` FOREIGN KEY (`q_id`) REFERENCES `Question` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
