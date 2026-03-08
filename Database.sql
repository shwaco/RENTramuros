CREATE TABLE Roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL
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
    passenger_capacity INT NOT NULL,
    hourly_rate DECIMAL(10,2) NOT NULL
);

CREATE TABLE System_Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    FOREIGN KEY (role_id) REFERENCES Roles(role_id)
);

CREATE TABLE Scheduled_Tours (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    route_id INT,
    vehicle_id INT,
    departure_datetime DATETIME NOT NULL,
    FOREIGN KEY (route_id) REFERENCES Public_Tour_Routes(route_id),
    FOREIGN KEY (vehicle_id) REFERENCES Vehicles(vehicle_id)
);

CREATE TABLE Reservations (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    booking_date DATETIME NOT NULL,
    status VARCHAR(50) NOT NULL,
    booking_type VARCHAR(50) NOT NULL,
    created_by_user_id INT NULL, -- NULL allows customers to book it themselves online
    FOREIGN KEY (customer_id) REFERENCES Customers(customer_id),
    FOREIGN KEY (created_by_user_id) REFERENCES System_Users(user_id)
);

CREATE TABLE Attraction_Bookings (
    attraction_booking_id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT,
    attraction_id INT,
    visit_date DATE NOT NULL,
    ticket_quantity INT NOT NULL,
    FOREIGN KEY (reservation_id) REFERENCES Reservations(reservation_id),
    FOREIGN KEY (attraction_id) REFERENCES Attractions(attraction_id)
);

CREATE TABLE Invoices (
    invoice_id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    FOREIGN KEY (reservation_id) REFERENCES Reservations(reservation_id)
);

CREATE TABLE Payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT,
    customer_id INT,
    transaction_type VARCHAR(50) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    linked_payment_id INT NULL, -- For refunds or adjustments, links to original payment
    processed_by_user_id INT,
    timestamp DATETIME NOT NULL,
    FOREIGN KEY (invoice_id) REFERENCES Invoices(invoice_id),
    FOREIGN KEY (customer_id) REFERENCES Customers(customer_id),
    FOREIGN KEY (linked_payment_id) REFERENCES Payments(payment_id),
    FOREIGN KEY (processed_by_user_id) REFERENCES System_Users(user_id)
);