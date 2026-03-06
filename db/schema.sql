CREATE DATABASE IF NOT EXISTS animal_adoption;
USE animal_adoption;

CREATE TABLE IF NOT EXISTS animals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  species VARCHAR(100) NOT NULL,
  age INT NOT NULL,
  gender ENUM('MALE', 'FEMALE') NOT NULL,
  color VARCHAR(100) NOT NULL,
  health_status ENUM('HEALTHY', 'UNDER_TREATMENT') NOT NULL, 
  picture_url VARCHAR(255),
  description TEXT, 
  is_adopted BOOLEAN DEFAULT FALSE NOT NULL, 
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO animals (name, species, age, gender, color, health_status, picture_url, description) VALUES
('Buddy', 'Dog', 3, 'MALE', 'Golden', 'HEALTHY', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRiW5I4OaNWln-kf44VLoHOTSBN7to0RWoSVutiJv1crvBd8yFcEIGjRmZOTrrkCiIPP45R8HxGZL--In9vQzJwFk0W7A_LK1fdSCElPCE&s=10', 'A friendly golden retriever who loves playing fetch.'),
('Luna', 'Cat', 2, 'FEMALE', 'White', 'HEALTHY', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQG9EmK1eL4LZ2hnNgtFazZOJpM8bCK3vU-xWW9MFoFUBnWwty8', 'A calm and affectionate cat looking for a quiet home.'),
('Max', 'Dog', 5, 'MALE', 'Black', 'UNDER_TREATMENT', 'https://www.thesprucepets.com/thmb/aj03cz24kxgTlrA6x67Bm2O6hbM=/750x0/filters:no_upscale():max_bytes(150000):strip_icc():format(webp)/GettyImages-1163899532-2-abe05a90e74e418cafa2823d7bb9dd92.jpg', 'Currently recovering from a paw injury, but very brave.'),
('Bella', 'Rabbit', 1, 'FEMALE', 'Grey', 'HEALTHY', 'https://media.istockphoto.com/id/137523108/photo/french-lop-rabbit-2-months-old-oryctolagus-cuniculus-sitting.jpg?s=612x612&w=0&k=20&c=EsYVL3s8DvcBg8mIFLrWu77dQdcd7IrafaTMuVpmt3w=', 'Enjoys carrots and plenty of space to hop around.'),
('Charlie', 'Cat', 4, 'MALE', 'Ginger', 'HEALTHY', 'https://i.guim.co.uk/img/media/327aa3f0c3b8e40ab03b4ae80319064e401c6fbc/377_133_3542_2834/master/3542.jpg?width=1200&height=1200&quality=85&auto=format&fit=crop&s=34d32522f47e4a67286f9894fc81c863', 'A very vocal ginger cat who loves attention.');