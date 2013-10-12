CREATE TABLE `tbl_users` (
  `id`        INT(11)      NOT NULL AUTO_INCREMENT,
  `username`  VARCHAR(20)  NOT NULL,
  `password`  VARCHAR(128) NOT NULL,
  `email`     VARCHAR(128) NOT NULL,
  `activkey`  VARCHAR(128) NOT NULL DEFAULT '',
  `create_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit` TIMESTAMP    NOT NULL DEFAULT '0000-00-00 00:00:00',
  `superuser` INT(1)       NOT NULL DEFAULT '0',
  `status`    INT(1)       NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `superuser` (`superuser`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =3;

CREATE TABLE `tbl_profiles` (
  `user_id`   INT(11)     NOT NULL AUTO_INCREMENT,
  `lastname`  VARCHAR(50) NOT NULL DEFAULT '',
  `firstname` VARCHAR(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8;

ALTER TABLE `tbl_profiles`
ADD CONSTRAINT `user_profile_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`)
  ON DELETE CASCADE;

CREATE TABLE `tbl_profiles_fields` (
  `id`              INT(10)       NOT NULL AUTO_INCREMENT,
  `varname`         VARCHAR(50)   NOT NULL,
  `title`           VARCHAR(255)  NOT NULL,
  `field_type`      VARCHAR(50)   NOT NULL,
  `field_size`      VARCHAR(15)   NOT NULL DEFAULT '0',
  `field_size_min`  VARCHAR(15)   NOT NULL DEFAULT '0',
  `required`        INT(1)        NOT NULL DEFAULT '0',
  `match`           VARCHAR(255)  NOT NULL DEFAULT '',
  `range`           VARCHAR(255)  NOT NULL DEFAULT '',
  `error_message`   VARCHAR(255)  NOT NULL DEFAULT '',
  `other_validator` VARCHAR(5000) NOT NULL DEFAULT '',
  `default`         VARCHAR(255)  NOT NULL DEFAULT '',
  `widget`          VARCHAR(255)  NOT NULL DEFAULT '',
  `widgetparams`    VARCHAR(5000) NOT NULL DEFAULT '',
  `position`        INT(3)        NOT NULL DEFAULT '0',
  `visible`         INT(1)        NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`, `widget`, `visible`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =3;


INSERT INTO `tbl_users` (`id`, `username`, `password`, `email`, `activkey`, `superuser`, `status`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'webmaster@example.com', '9a24eff8c15a6a141ece27eb6947da0f', 1, 1),
(2, 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 'demo@example.com', '099f825543f7850cc038b90aaff39fac', 0, 1);

INSERT INTO `tbl_profiles` (`user_id`, `lastname`, `firstname`) VALUES
(1, 'Admin', 'Administrator'),
(2, 'Demo', 'Demo');

INSERT INTO `tbl_profiles_fields` (`id`, `varname`, `title`, `field_type`, `field_size`, `field_size_min`, `required`, `match`, `range`, `error_message`, `other_validator`, `default`, `widget`, `widgetparams`, `position`, `visible`) VALUES
(1, 'lastname', 'Last Name', 'VARCHAR', 50, 3, 1, '', '', 'Incorrect Last Name (length between 3 and 50 characters).', '', '', '', '', 1, 3),
(2, 'firstname', 'First Name', 'VARCHAR', 50, 3, 1, '', '', 'Incorrect First Name (length between 3 and 50 characters).', '', '', '', '', 0, 3);