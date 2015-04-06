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
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
  PRIMARY KEY (`users_id`),
  UNIQUE KEY `users_id` (`users_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_user_groups` (
  `user_groups_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_groups_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_user_and_group_connection` (
  `user_and_group_connection_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `users_id` int(15) NOT NULL,
  `user_groups_id` int(15) NOT NULL,
  PRIMARY KEY (`user_and_group_connection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_user_group_permissions` (
  `user_group_permissions_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_group_permissions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_user_group_and_group_permission_connection` (
  `user_group_and_group_permission_connection_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_groups_id` int(15) NOT NULL,
  `user_group_permissions_id` int(15) NOT NULL,
  PRIMARY KEY (`user_group_and_group_permission_connection_id`)
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

CREATE TABLE IF NOT EXISTS `szcm3_user_staged_changes` (
  `user_staged_changes_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
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
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `users_id` int(15) NOT NULL,
  PRIMARY KEY (`user_staged_changes_id`),
  UNIQUE KEY `user_staged_changes_id` (`user_staged_changes_id`),
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

CREATE TABLE IF NOT EXISTS `szcm3_convention_registrations` (
  `convention_registrations_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `users_id` int(15) NOT NULL,
  `entrance_type` int(15) NOT NULL,
  `member` int(11) NOT NULL DEFAULT '0',
  `mug` int(11) NOT NULL DEFAULT '0',
  `payment_registered` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`convention_registrations_id`),
  UNIQUE KEY `users_id` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_convention_registration_form` (
  `convention_registration_form_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `belongs_to_registration_period` int(15) NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL,
  `if_member_price_reduced_by` int(15) NOT NULL DEFAULT '0',
  `price` int(15) NOT NULL DEFAULT '0',
  PRIMARY KEY (`convention_registration_form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `szcm3_convention_registration_periods` (
  `convention_registration_periods_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `description` varchar(255) NOT NULL,
  `last_registration_date` datetime NOT NULL,
  PRIMARY KEY (`convention_registration_periods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
 * Basic setup for a convention with preregistrations...
 */

INSERT INTO `szcm3_convention_registration_periods`(`convention_registration_periods_id`, `date_created`, `date_updated`, `description`, `last_registration_date`) VALUES (1,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,'Föranmälan slutar', '2015-06-01 12:00:00');

INSERT INTO `szcm3_convention_registration_periods`(`convention_registration_periods_id`, `date_created`, `date_updated`, `description`, `last_registration_date`) VALUES (2,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,'Konventet slutar', '2015-07-05 23:59:59');
 
INSERT INTO `szcm3_convention_registration_form`(`date_created`, `date_updated`, `belongs_to_registration_period`, `description`, `if_member_price_reduced_by`, `price`) VALUES (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,1,'Inträde WSK 2015, hela konventet',-150,300);

INSERT INTO `szcm3_convention_registration_form`(`date_created`, `date_updated`, `belongs_to_registration_period`, `description`, `if_member_price_reduced_by`, `price`) VALUES (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,1,'Inträde WSK 2015, fredag',-150,150);

INSERT INTO `szcm3_convention_registration_form`(`date_created`, `date_updated`, `belongs_to_registration_period`, `description`, `if_member_price_reduced_by`, `price`) VALUES (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,1,'Inträde WSK 2015, lördag',-150,200);

INSERT INTO `szcm3_convention_registration_form`(`date_created`, `date_updated`, `belongs_to_registration_period`, `description`, `if_member_price_reduced_by`, `price`) VALUES (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,1,'Inträde WSK 2015, söndag',-150,150);

INSERT INTO `szcm3_convention_registration_form`(`date_created`, `date_updated`, `belongs_to_registration_period`, `description`, `if_member_price_reduced_by`, `price`) VALUES (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,1,'Inget inträde, jag vill bara stöja föreningen och/eller är under 13 år',0,0);

INSERT INTO `szcm3_convention_registration_form`(`date_created`, `date_updated`, `belongs_to_registration_period`, `description`, `if_member_price_reduced_by`, `price`) VALUES (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,2,'Inträde WSK 2015, hela konventet',-150,300);

INSERT INTO `szcm3_convention_registration_form`(`date_created`, `date_updated`, `belongs_to_registration_period`, `description`, `if_member_price_reduced_by`, `price`) VALUES (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,2,'Inträde WSK 2015, fredag',-150,150);

INSERT INTO `szcm3_convention_registration_form`(`date_created`, `date_updated`, `belongs_to_registration_period`, `description`, `if_member_price_reduced_by`, `price`) VALUES (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,2,'Inträde WSK 2015, lördag',-150,200);

INSERT INTO `szcm3_convention_registration_form`(`date_created`, `date_updated`, `belongs_to_registration_period`, `description`, `if_member_price_reduced_by`, `price`) VALUES (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,2,'Inträde WSK 2015, söndag',-150,150);

INSERT INTO `szcm3_convention_registration_form`(`date_created`, `date_updated`, `belongs_to_registration_period`, `description`, `if_member_price_reduced_by`, `price`) VALUES (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,2,'Inget inträde, jag vill bara stöja föreningen och/eller är under 13 år',0,0);

/* Usergroups */
INSERT INTO `szcm3_user_groups`(`user_groups_id`, `description`) VALUES (1,'regular user');
INSERT INTO `szcm3_user_groups`(`user_groups_id`, `description`) VALUES (2,'stab');
INSERT INTO `szcm3_user_groups`(`user_groups_id`, `description`) VALUES (3,'admin');
INSERT INTO `szcm3_user_groups`(`user_groups_id`, `description`) VALUES (4,'economist');

/* permissions */
INSERT INTO `szcm3_user_group_permissions`(`user_group_permissions_id`, `description`) VALUES (1,'PERM_VIEW_ALL_EVENTS');
INSERT INTO `szcm3_user_group_permissions`(`user_group_permissions_id`, `description`) VALUES (2,'PERM_CREATE_NEW_EVENTS');
INSERT INTO `szcm3_user_group_permissions`(`user_group_permissions_id`, `description`) VALUES (3,'PERM_EDIT_ALL_EVENTS');
INSERT INTO `szcm3_user_group_permissions`(`user_group_permissions_id`, `description`) VALUES (4,'PERM_WITHDRAW_CONFIRMED_EVENTS');
INSERT INTO `szcm3_user_group_permissions`(`user_group_permissions_id`, `description`) VALUES (5,'PERM_DELETE_AND_CONFIRM_EVENTS');
INSERT INTO `szcm3_user_group_permissions`(`user_group_permissions_id`, `description`) VALUES (6,'PERM_ADD_EVENT_TO_SCHEDULE');
INSERT INTO `szcm3_user_group_permissions`(`user_group_permissions_id`, `description`) VALUES (7,'PERM_COMFIRM_NEW_USER_DETAILS');
INSERT INTO `szcm3_user_group_permissions`(`user_group_permissions_id`, `description`) VALUES (8,'PERM_COMFIRM_USER_PAYMENTS');

INSERT INTO `szcm3_user_group_and_group_permission_connection`(`user_groups_id`, `user_group_permissions_id`) VALUES (2,1);
INSERT INTO `szcm3_user_group_and_group_permission_connection`(`user_groups_id`, `user_group_permissions_id`) VALUES (2,2);
INSERT INTO `szcm3_user_group_and_group_permission_connection`(`user_groups_id`, `user_group_permissions_id`) VALUES (2,3);
INSERT INTO `szcm3_user_group_and_group_permission_connection`(`user_groups_id`, `user_group_permissions_id`) VALUES (2,4);
INSERT INTO `szcm3_user_group_and_group_permission_connection`(`user_groups_id`, `user_group_permissions_id`) VALUES (2,5);
INSERT INTO `szcm3_user_group_and_group_permission_connection`(`user_groups_id`, `user_group_permissions_id`) VALUES (2,6);
INSERT INTO `szcm3_user_group_and_group_permission_connection`(`user_groups_id`, `user_group_permissions_id`) VALUES (3,7);
INSERT INTO `szcm3_user_group_and_group_permission_connection`(`user_groups_id`, `user_group_permissions_id`) VALUES (4,8);