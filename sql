CREATE DATABASE shopping_cart;

CREATE TABLE `products` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `description` TEXT,
    `image` VARCHAR(255),
    PRIMARY KEY (`id`)
);

INSERT INTO `products` (`name`, `price`, `description`, `image`) VALUES
('watch', 100.00, 'Description for watch', 'product1.jpg'),
('shoes', 200.00, 'Description for shoes', 'product2.jpg'),
('headphones', 150.00, 'Description for headphones', 'product3.jpg');


CREATE TABLE `orders` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `customer_name` VARCHAR(100) NOT NULL,
    `customer_email` VARCHAR(100) NOT NULL,
    `order_date` DATETIME NOT NULL,
    `total` DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `order_items` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `order_id` INT(11) NOT NULL,
    `product_id` INT(11) NOT NULL,
    `product_name` VARCHAR(100) NOT NULL,
    `product_price` DECIMAL(10,2) NOT NULL,
    `quantity` INT(11) NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`)
);
