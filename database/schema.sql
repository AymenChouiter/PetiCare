CREATE DATABASE IF NOT EXISTS peticare_db;
USE peticare_db;

CREATE TABLE IF NOT EXISTS animals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  species VARCHAR(100) NOT NULL,
  gender ENUM('MALE', 'FEMALE') NOT NULL,
  birth_date DATE NOT NULL,
  description TEXT,
  health_status ENUM('HEALTHY', 'UNDER_TREATMENT') NOT NULL DEFAULT 'HEALTHY',
  adoption_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  adoption_status ENUM('AVAILABLE', 'RESERVED', 'ADOPTED') NOT NULL DEFAULT 'AVAILABLE',
  picture_data LONGBLOB, 
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- SEEDING TEST DATA
INSERT INTO animals (
  name, 
  species, 
  gender, 
  birth_date, 
  description, 
  health_status, 
  adoption_fee, 
  adoption_status, 
  picture_data
) VALUES 
('Max', 'Dog', 'MALE', '2021-04-12', 'A friendly Golden Retriever who loves kids.', 'HEALTHY', 150.00, 'AVAILABLE', NULL),
('Bella', 'Cat', 'FEMALE', '2022-08-30', 'Very calm and loves to nap in sunny spots.', 'HEALTHY', 80.00, 'AVAILABLE', NULL),
('Charlie', 'Dog', 'MALE', '2023-01-15', 'Energetic Husky pup, needs a lot of exercise.', 'UNDER_TREATMENT', 0.00, 'RESERVED', NULL),
('Luna', 'Rabbit', 'FEMALE', '2023-05-20', 'Sweet rabbit, likes carrots and quiet spaces.', 'HEALTHY', 45.00, 'AVAILABLE', NULL),
('Rocky', 'Bird', 'MALE', '2020-11-05', 'Talkative parrot who can whistle 3 tunes.', 'HEALTHY', 120.00, 'ADOPTED', NULL);