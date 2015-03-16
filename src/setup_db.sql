CREATE TABLE IF NOT EXISTS `szcm3_data_throttling` (
  `data_throttling_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `in_progress_since` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `channel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `data_throttling_id` (`data_throttling_id`),
  KEY `events_id` (`data_throttling_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_events` (
  `events_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8_unicode_ci NOT NULL,
  `long_description` mediumtext COLLATE utf8_unicode_ci,
  `event_type` int(3) NOT NULL,
  `contact` int(15) NOT NULL,
  `functions_id` int(15) NOT NULL,
  `approved` int(1) NOT NULL DEFAULT '0',
  `fee` int(11) NOT NULL DEFAULT '0',
  `pre_registration` int(1) NOT NULL DEFAULT '1',
  `contact_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`events_id`),
  UNIQUE KEY `functions_id` (`events_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_event_schedule` (
  `event_schedule_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `events_id` int(15) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cancelled` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_schedule_id`),
  UNIQUE KEY `functions_id` (`event_schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_event_types` (
  `event_types_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `staff_exclusive` int(1) NOT NULL DEFAULT '0',
  `order` int(15) NOT NULL,
  PRIMARY KEY (`event_types_id`),
  UNIQUE KEY `functions_id` (`event_types_id`),
  UNIQUE KEY `text` (`text`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_functions` (
  `functions_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`functions_id`),
  UNIQUE KEY `functions_id` (`functions_id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_function_details` (
  `function_details_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `long_description` mediumtext COLLATE utf8_unicode_ci,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `location_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `functions_id` int(15) NOT NULL,
  PRIMARY KEY (`function_details_id`),
  UNIQUE KEY `functions_id` (`function_details_id`),
  UNIQUE KEY `functions_id_2` (`functions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_users` (
  `users_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_type` int(2) NOT NULL DEFAULT '2',
  PRIMARY KEY (`users_id`),
  UNIQUE KEY `users_id` (`users_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_user_accounts` (
  `user_accounts_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `account_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `account_type` int(2) NOT NULL,
  PRIMARY KEY (`user_accounts_id`),
  UNIQUE KEY `users_id` (`user_accounts_id`),
  UNIQUE KEY `username` (`account_text`),
  UNIQUE KEY `account_type` (`account_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_user_details` (
  `user_details_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `given_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `family_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `male` int(1) DEFAULT NULL,
  `national_id_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `users_id` int(15) NOT NULL,
  PRIMARY KEY (`user_details_id`),
  UNIQUE KEY `user_details_id` (`user_details_id`),
  UNIQUE KEY `users_id` (`users_id`),
  UNIQUE KEY `national_id_number` (`national_id_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_user_social` (
  `user_social_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `social_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `users_id` int(15) NOT NULL,
  PRIMARY KEY (`user_social_id`),
  UNIQUE KEY `user_details_id` (`user_social_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;