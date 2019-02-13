/* Основная таблица */
CREATE TABLE IF NOT EXISTS `goods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` BIGINT NOT NULL,
  `image` varchar(255),
  `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE INDEX index_goods_price ON goods (price);

/* Тестовые данные */
INSERT INTO goods (image, name, description, price) VALUES ("img1.jpg", "Плот", "Небольшой вместительный плот", "10000");
INSERT INTO goods (image, name, description, price) VALUES ("img2.jpg", "Ганзейский корабль", "Огромный парусник, оснащенный по последнему слову техники 15 века", "1000000");
INSERT INTO goods (image, name, description, price) VALUES ("img3.jpg", "Фрегат", "Военный парусный корабль, типа фрегат", "50050");
INSERT INTO goods (image, name, description, price) VALUES ("img4.jpg", "Автомобиль Левассор", "Раритетное авто 1903 года, в хорошем состоянии", "9900000099");
INSERT INTO goods (image, name, description, price) VALUES ("img5.jpg", "Даймлер", "Покоритель сердец и звезда мирового автопрома", "9999999999");