-- 1. Ensure we are targeting the exact database name from your screenshot
USE rentramuros_database;

-- 2. Temporarily turn off relationship rules so we can safely delete old tables
SET FOREIGN_KEY_CHECKS = 0;

-- 3. Wipe the slate clean of any half-finished tables from previous attempts
DROP TABLE IF EXISTS Payments, Invoices, Package_Bookings, Attraction_Bookings, Reservations, Package_Itinerary, Packages, Vehicles, Attractions, Customers, Admins, Tour_Guides;

-- 4. Turn the safety rules back on
SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================================
-- LEVEL 1: Independent Tables (No Foreign Keys)
-- =========================================================================

CREATE TABLE Admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE Tour_Guides (
    guide_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    languages_spoken VARCHAR(100)
);

CREATE TABLE Customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20)
);

CREATE TABLE Attractions (
    attraction_id INT AUTO_INCREMENT PRIMARY KEY,
    attraction_name VARCHAR(100) NOT NULL,
    entrance_fee DECIMAL(10,2) NOT NULL,
    operating_hours VARCHAR(100)
);

CREATE TABLE Vehicles (
    vehicle_id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_type VARCHAR(50) NOT NULL, 
    passenger_capacity INT NOT NULL
);

CREATE TABLE Packages (
    package_id INT AUTO_INCREMENT PRIMARY KEY,
    package_name VARCHAR(150) NOT NULL,
    description VARCHAR(255),
    price_per_person DECIMAL(10,2) NOT NULL
);

-- =========================================================================
-- LEVEL 2: Intermediate Tables
-- =========================================================================

CREATE TABLE Package_Itinerary (
    package_itinerary_id INT AUTO_INCREMENT PRIMARY KEY,
    package_id INT,
    attraction_id INT,
    visit_order INT NOT NULL,
    FOREIGN KEY (package_id) REFERENCES Packages(package_id),
    FOREIGN KEY (attraction_id) REFERENCES Attractions(attraction_id)
);

CREATE TABLE Reservations (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    booking_date DATETIME NOT NULL,
    status VARCHAR(50) NOT NULL, 
    booking_type VARCHAR(50) NOT NULL, 
    created_by_admin_id INT NULL, 
    FOREIGN KEY (customer_id) REFERENCES Customers(customer_id),
    FOREIGN KEY (created_by_admin_id) REFERENCES Admins(admin_id)
);

-- =========================================================================
-- LEVEL 3: Bookings and Invoices
-- =========================================================================

CREATE TABLE Attraction_Bookings (
    attraction_booking_id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT,
    attraction_id INT,
    visit_date DATE NOT NULL,
    ticket_quantity INT NOT NULL,
    FOREIGN KEY (reservation_id) REFERENCES Reservations(reservation_id),
    FOREIGN KEY (attraction_id) REFERENCES Attractions(attraction_id)
);

CREATE TABLE Package_Bookings (
    package_booking_id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT,
    package_id INT,
    vehicle_id INT,
    guide_id INT NULL, 
    tour_date DATE NOT NULL,
    passenger_count INT NOT NULL,
    FOREIGN KEY (reservation_id) REFERENCES Reservations(reservation_id),
    FOREIGN KEY (package_id) REFERENCES Packages(package_id),
    FOREIGN KEY (vehicle_id) REFERENCES Vehicles(vehicle_id),
    FOREIGN KEY (guide_id) REFERENCES Tour_Guides(guide_id)
);

CREATE TABLE Invoices (
    invoice_id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    FOREIGN KEY (reservation_id) REFERENCES Reservations(reservation_id)
);

-- =========================================================================
-- LEVEL 4: Payments (With 2-Step Fix)
-- =========================================================================

-- Step 1: Create the table without the self-referencing refund link
CREATE TABLE Payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT,
    customer_id INT,
    transaction_type VARCHAR(50) NOT NULL, 
    amount DECIMAL(10,2) NOT NULL,
    linked_payment_id INT NULL, 
    processed_by_admin_id INT NULL, 
    timestamp DATETIME NOT NULL,
    FOREIGN KEY (invoice_id) REFERENCES Invoices(invoice_id),
    FOREIGN KEY (customer_id) REFERENCES Customers(customer_id),
    FOREIGN KEY (processed_by_admin_id) REFERENCES Admins(admin_id)
);

-- Step 2: Add the self-referencing link for refunds
ALTER TABLE Payments
ADD FOREIGN KEY (linked_payment_id) REFERENCES Payments(payment_id);