# Chittagong University Lost and Found System

## Description

The **Chittagong University Lost and Found System** is a web-based application designed to assist students and faculty in reporting and managing lost and found items. The platform simplifies locating misplaced items and facilitates communication between item owners and finders.

## Features

- **User Authentication**: Secure user signup and login with session management. 
- **Lost & Found Reports**: Users can report lost or found items with detailed descriptions and images. Supports viewing and managing reports. 
- **Search and Browse**: Users can browse reported items with filtering options. View item details and contact the reporters. 
- **Charts and Insights**: Dynamic graphical representations of data using Chart.js. 
- **CRUD Operations**: Full support for creating, reading, updating, and deleting reports. 
- **Profile Management**: Users can view and edit their profiles. Manage their past activity.

## Technologies Used

- **Frontend**: HTML, Tailwind CSS for modern styling, Chart.js for graphical insights and data visualization 
- **Backend**: PHP with PDO for secure database interaction 
- **Database**: MySQL for data storage and management 
- **Server**: Apache (XAMPP) for local development and deployment

## Setup Instructions

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/EmranHoque/DatabaseProject-Chittagong-University-Lost-and-Found-System.git
2. **Configure the Database:**:
  - Install and configure Apache, PHP, and MySQL.
  - Place the project files in your web server’s root directory (e.g., htdocs for XAMPP).
3. **Set Up the Server:**:
  - Create a new MySQL database.
  - Import the SQL schema located in the /schema directory.
  - Update the database connection details in db/db.php to match your setup.
4. **Run the Application:**:
  - Start your Apache and MySQL server.
  - Access the application via your browser by navigating to http://localhost/index.php.


