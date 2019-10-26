--
-- Table structure for table users
--
CREATE TABLE users (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table users
--
ALTER TABLE users
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);
--
-- AUTO_INCREMENT for table users
--
ALTER TABLE users
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Table structure for table `remembered_logins`
--
CREATE TABLE remembered_logins (
  `token_hash` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Indexes for table `remembered_logins`
--
ALTER TABLE `remembered_logins`
  ADD PRIMARY KEY (`token_hash`),
  ADD KEY `user_id` (`user_id`);
--
-- Additional columns for password reset
--
ALTER TABLE `users`
    ADD `password_reset_hash` VARCHAR(64) NULL DEFAULT NULL AFTER `password_hash`,
    ADD `password_reset_expires_at` DATETIME NULL DEFAULT NULL AFTER `password_reset_hash`,
    ADD UNIQUE (`password_reset_hash`);