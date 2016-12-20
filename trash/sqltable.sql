CREATE TABLE products (
  product_id INT NOT NULL AUTO_INCREMENT,
  product_name VARCHAR(80) NOT NULL,
  product_price INT(10) NOT NULL,
  product_currency VARCHAR(3) NOT NULL,
  product_owner VARCHAR(20) NOT NULL,
  product_description TEXT(200) NOT NULL,
  product_images_src VARCHAR(100),
  PRIMARY KEY (product_id)
) CHARACTER SET utf8