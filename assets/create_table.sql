CREATE TABLE `/*TABLE_PREFIX*/t_item_durations` (
  `fk_i_item_id` int(10) UNSIGNED NOT NULL,
  `i_duration` int(2) DEFAULT NULL,
  `s_block` int(1) DEFAULT NULL,
  PRIMARY KEY (`fk_i_item_id`),
  KEY `fk_i_item_id` (`fk_i_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `/*TABLE_PREFIX*/t_item_durations`
  ADD CONSTRAINT `/*TABLE_PREFIX*/t_item_durations_ibfk_1` FOREIGN KEY (`fk_i_item_id`) REFERENCES `/*TABLE_PREFIX*/t_item` (`pk_i_id`);