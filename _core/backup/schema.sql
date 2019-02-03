CREATE TABLE IF NOT EXISTS `prefix_bans` (
  `ban_id` int(11) NOT NULL AUTO_INCREMENT,
  `ban_ip` varchar(46) COLLATE utf8_czech_ci NOT NULL COMMENT 'Banned IP',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Banned User',
  `ban_time` int(10) NOT NULL,
  `ban_time_end` int(10) NOT NULL,
  `ban_reason` text COLLATE utf8_czech_ci NOT NULL,
  `ban_user_id` int(11) NOT NULL COMMENT 'Ban by Admin',
  PRIMARY KEY (`ban_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text COLLATE utf8_czech_ci NOT NULL,
  `comment_timestamp` int(10) NOT NULL,
  `comment_timestamp_edit` int(10) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_crons` (
  `cron_id` int(11) NOT NULL AUTO_INCREMENT,
  `cron_name` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `cron_path` text COLLATE utf8_czech_ci NOT NULL,
  `cron_timer` int(10) NOT NULL,
  `cron_time` int(10) NOT NULL,
  PRIMARY KEY (`cron_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `prefix_devices` (
  `device_id` int(11) NOT NULL AUTO_INCREMENT,
  `device_ip` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `device_agent` text COLLATE utf8_czech_ci NOT NULL,
  `session_id` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `device_auth_key` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `device_login_key` varchar(64) COLLATE utf8_czech_ci DEFAULT NULL,
  `device_timestamp_register` int(10) NOT NULL,
  `device_timestamp_active` int(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `device_status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`device_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_keys` (
  `key_id` int(11) NOT NULL AUTO_INCREMENT,
  `key` text COLLATE utf8_czech_ci NOT NULL,
  `key_time` int(10) NOT NULL,
  `key_type` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `key_data` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`key_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_level` int(2) NOT NULL,
  `log_time` int(10) NOT NULL,
  `log_text` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `menu_link` text COLLATE utf8_czech_ci NOT NULL,
  `menu_dropdown` varchar(11) COLLATE utf8_czech_ci DEFAULT NULL,
  `menu_sid` int(5) NOT NULL,
  `menu_order` int(11) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_menus` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `menu_pos` int(3) NOT NULL,
  `menu_sid` int(11) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_subject` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `message_text` text COLLATE utf8_czech_ci NOT NULL,
  `message_type` int(1) NOT NULL DEFAULT '0',
  `user_id_s` int(11) NOT NULL COMMENT 'send',
  `user_id_r` int(11) NOT NULL COMMENT 'read',
  `message_timestamp_send` int(10) NOT NULL,
  `message_timestamp_showed` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `prefix_newsCategories` (
  `newsCategory_id` int(11) NOT NULL AUTO_INCREMENT,
  `newsCategory` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `newsCategory_desc` text COLLATE utf8_czech_ci,
  `newsCategory_url` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`newsCategory_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_plugins` (
  `plugin_id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_name` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `plugin_url` text COLLATE utf8_czech_ci NOT NULL,
  `plugin_dir` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `plugin_desc` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`plugin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_title` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `post_content` text COLLATE utf8_czech_ci NOT NULL,
  `post_type` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_timestamp_add` int(10) NOT NULL,
  `edit_user_id` int(11) NOT NULL,
  `post_timestamp_edit` int(10) NOT NULL,
  `post_comments` int(1) NOT NULL,
  `post_visibility` int(1) NOT NULL,
  `post_url` text COLLATE utf8_czech_ci NOT NULL,
  `newsCategory_id` int(11) NOT NULL DEFAULT '0',
  `post_tags` text COLLATE utf8_czech_ci NOT NULL,
  `post_description` text COLLATE utf8_czech_ci NOT NULL,
  `post_keywords` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_reports` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `report_type` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `report_content` text COLLATE utf8_czech_ci NOT NULL,
  `report_timestamp` int(10) NOT NULL,
  `report_timestamp_show` int(10) DEFAULT '0',
  `report_admin` int(11) DEFAULT '0',
  `report_reported` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message_id` int(11) DEFAULT '0',
  `report_timestamp_solved` int(10) DEFAULT '0',
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `prefix_settings` (
  `settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `settings_name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `settings_value` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_sidebars` (
  `sidebar_id` int(11) NOT NULL AUTO_INCREMENT,
  `sidebar_name` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `sidebar_title` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `sidebar_content_type` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  `sidebar_content` text COLLATE utf8_czech_ci NOT NULL,
  `sidebar_pos` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  `sidebar_visibility` int(1) NOT NULL,
  `sidebar_class` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `sidebar_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sidebar_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_userGroups` (
  `usergroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `usergroup_name` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `usergroup_desc` text COLLATE utf8_czech_ci NOT NULL,
  `usergroup_label` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `usergroup_color_bg` varchar(6) COLLATE utf8_czech_ci NOT NULL,
  `usergroup_color_text` varchar(6) COLLATE utf8_czech_ci NOT NULL,
  `usergroup_link` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `usergroup_rights` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`usergroup_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `user_pass` text COLLATE utf8_czech_ci NOT NULL,
  `user_salt` text COLLATE utf8_czech_ci NOT NULL,
  `user_display_name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `user_email` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  `user_timestamp_register` int(10) NOT NULL,
  `user_timestamp_login` int(10) NOT NULL,
  `user_timestamp_active` int(10) NOT NULL,
  `user_groups` text COLLATE utf8_czech_ci NOT NULL,
  `user_rights` int(11) NOT NULL,
  `user_rights_detail` text COLLATE utf8_czech_ci NOT NULL,
  `user_login_type` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_userSettings` (
  `userSettings_id` int(11) NOT NULL AUTO_INCREMENT,
  `userSettings_name` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `userSettings_value` text COLLATE utf8_czech_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`userSettings_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;