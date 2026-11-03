CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('employee','admin') NOT NULL DEFAULT 'employee',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  capacity INT NOT NULL,
  building VARCHAR(100),
  floor INT,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS equipments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS equipment_room (
  room_id INT NOT NULL,
  equipment_id INT NOT NULL,
  PRIMARY KEY (room_id, equipment_id),
  FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
  FOREIGN KEY (equipment_id) REFERENCES equipments(id) ON DELETE CASCADE
);

INSERT INTO rooms (name, capacity, building, floor) VALUES
('Salle 1', 10, 'Bâtiment A', 1),
('Salle 2', 20, 'Bâtiment A', 2),
('Salle 3', 6, 'Bâtiment B', 1);

INSERT INTO equipments (name) VALUES
('Vidéoprojecteur'), ('Tableau blanc'), ('Visioconférence');

INSERT INTO equipment_room (room_id, equipment_id) VALUES
(1, 1), (1, 2), (2, 1), (2, 3), (3, 2);