CREATE DATABASE aquafin_app;

USE aquafin_app;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(255),
  role VARCHAR(20)
);

CREATE TABLE materials (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  quantity INT
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  material_id INT,
  start_date DATE,
  end_date DATE,
  quantity INT DEFAULT 1,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (material_id) REFERENCES materials(id)
);


