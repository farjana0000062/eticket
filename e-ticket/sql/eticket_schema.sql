-- Create database
CREATE DATABASE IF NOT EXISTS eticket_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE eticket_db;

-- Stations
CREATE TABLE stations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  code VARCHAR(10) NOT NULL UNIQUE
);

-- Trains
CREATE TABLE trains (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  number VARCHAR(20) NOT NULL UNIQUE,
  total_seats INT NOT NULL DEFAULT 100
);

-- Schedules (train runs between station_from -> station_to on a date/time)
CREATE TABLE schedules (
  id INT AUTO_INCREMENT PRIMARY KEY,
  train_id INT NOT NULL,
  station_from_id INT NOT NULL,
  station_to_id INT NOT NULL,
  depart DATETIME NOT NULL,
  arrive DATETIME NOT NULL,
  fare DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (train_id) REFERENCES trains(id) ON DELETE CASCADE,
  FOREIGN KEY (station_from_id) REFERENCES stations(id),
  FOREIGN KEY (station_to_id) REFERENCES stations(id)
);

-- Seats (per schedule; seat_number like A1,1..N)
CREATE TABLE seats (
  id INT AUTO_INCREMENT PRIMARY KEY,
  schedule_id INT NOT NULL,
  seat_no VARCHAR(10) NOT NULL,
  class ENUM('economy','business') DEFAULT 'economy',
  status ENUM('available','booked') DEFAULT 'available',
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (schedule_id) REFERENCES schedules(id) ON DELETE CASCADE,
  UNIQUE(schedule_id, seat_no)
);

-- Users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings
CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  schedule_id INT NOT NULL,
  seat_id INT NOT NULL,
  passenger_name VARCHAR(150) NOT NULL,
  passenger_age INT NULL,
  booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status ENUM('confirmed','cancelled') DEFAULT 'confirmed',
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (schedule_id) REFERENCES schedules(id),
  FOREIGN KEY (seat_id) REFERENCES seats(id)
);

-- Sample seed data
INSERT INTO stations (name, code) VALUES
  ('Dhaka', 'DHK'),
  ('Chittagong', 'CTG'),
  ('Sylhet', 'SYL');

INSERT INTO trains (name, number, total_seats) VALUES
  ('Intercity Express', '1001', 40),
  ('Coastal Mail', '2002', 60);

-- schedules (simple)
INSERT INTO schedules (train_id, station_from_id, station_to_id, depart, arrive, fare) VALUES
  (1, 1, 2, '2025-11-01 08:00:00', '2025-11-01 12:00:00', 500.00),
  (2, 1, 3, '2025-11-02 09:00:00', '2025-11-02 13:00:00', 450.00);

-- create seats for each schedule (simple: create N seats)
-- schedule 1: 10 seats
INSERT INTO seats (schedule_id, seat_no, price) VALUES
  (1,'1',500.00),(1,'2',500.00),(1,'3',500.00),(1,'4',500.00),(1,'5',500.00),
  (1,'6',500.00),(1,'7',500.00),(1,'8',500.00),(1,'9',500.00),(1,'10',500.00);

-- schedule 2: 8 seats
INSERT INTO seats (schedule_id, seat_no, price) VALUES
  (2,'1',450.00),(2,'2',450.00),(2,'3',450.00),(2,'4',450.00),
  (2,'5',450.00),(2,'6',450.00),(2,'7',450.00),(2,'8',450.00);
