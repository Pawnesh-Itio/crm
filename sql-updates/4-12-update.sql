
--
-- Table structure for table `it_crm_chat_archive`
--

CREATE TABLE `it_crm_chat_archive` (
  `id` int(11) NOT NULL,
  `client_id` bigint(20) NOT NULL,
  `chat_message` longtext NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `it_crm_chat_archive`
--
ALTER TABLE `it_crm_chat_archive`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `it_crm_chat_archive`
--
ALTER TABLE `it_crm_chat_archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
