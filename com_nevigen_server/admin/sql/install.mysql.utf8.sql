/*
 * @package    Nevigen server package
 * @version    __DEPLOY_VERSION__
 * @author     Artem Pavluk - https://nevigen.com
 * @copyright  Copyright © Nevigen.com. All rights reserved.
 * @license    Proprietary. Copyrighted Commercial Software
 * @link       https://nevigen.com
 */
CREATE TABLE IF NOT EXISTS `#__nevigen_server_extensions`
(
    `id`      int(11)      NOT NULL AUTO_INCREMENT,
    `title`   varchar(255) NOT NULL DEFAULT '',
    `image`   varchar(255) NOT NULL DEFAULT '',
    `element` varchar(100) NOT NULL DEFAULT '',
    `type`    varchar(100) NOT NULL DEFAULT '',
    `paid`    tinyint(3)   NOT NULL DEFAULT 1,
    `version` text         NULL,
    `support` int(11)      NOT NULL DEFAULT 0,
    `state`   tinyint(3)   NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_element` (`element`(100)),
    KEY `idx_paid` (`paid`),
    KEY `idx_support` (`support`),
    KEY `idx_state` (`state`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    DEFAULT COLLATE = utf8mb4_unicode_ci
    AUTO_INCREMENT = 0;
CREATE TABLE IF NOT EXISTS `#__nevigen_server_orders`
(
    `id`           int(11)                                                NOT NULL AUTO_INCREMENT,
    `domain`       varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `extension`    int(10)                                                NOT NULL DEFAULT 0,
    `created`      datetime                                               NULL,
    `shutdown`     datetime                                               NULL,
    `created_by`   int(10) unsigned                                       NOT NULL DEFAULT 0,
    `joomshopping` int(11)                                                NOT NULL DEFAULT 0,
    `downloads`    int(10)                                                NOT NULL DEFAULT 0,
    `state`        tinyint(3)                                             NOT NULL DEFAULT 0,

    PRIMARY KEY `id` (`id`),
    KEY `idx_domain` (`domain`(100)),
    KEY `idx_extension` (`extension`),
    KEY `idx_created` (`created`),
    KEY `idx_shutdown` (`shutdown`),
    KEY `idx_createdby` (`created_by`),
    KEY `idx_joomshopping` (`joomshopping`),
    KEY `idx_state` (`state`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    DEFAULT COLLATE = utf8mb4_unicode_ci
    AUTO_INCREMENT = 0;
CREATE TABLE IF NOT EXISTS `#__nevigen_server_order_shortcodes`
(
    `id`       int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(10) NOT NULL DEFAULT 0,
    `number`   int(10) NOT NULL DEFAULT 0,
    `word`     text    NULL,
    `mix`      text    NULL,

    PRIMARY KEY `id` (`id`),
    KEY `idx_order` (`order_id`),
    KEY `idx_number` (`number`),
    KEY `idx_mix` (`mix`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    DEFAULT COLLATE = utf8mb4_unicode_ci
    AUTO_INCREMENT = 0;