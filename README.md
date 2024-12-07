# Chittagong University Lost and Found System

## Description

The **Chittagong University Lost and Found System** is a web-based application designed to assist students and faculty in reporting and managing lost and found items. The platform simplifies locating misplaced items and facilitates communication between item owners and finders.

## Features

- **User Authentication**: Secure user signup and login with session management.
- **Lost & Found Reports**: 
  - Users can report lost or found items with detailed descriptions and images.
  - Supports viewing and managing reports.
- **Search and Browse**: 
  - Users can browse reported items with filtering options.
  - View item details and contact the reporters.
- **Charts and Insights**: Dynamic graphical representations of data using Chart.js.
- **CRUD Operations**: Full support for creating, reading, updating, and deleting reports.
- **Profile Management**: 
  - Users can view and edit their profiles.
  - Manage their past activity.

## Technologies Used

- **Frontend**: 
  - HTML, Tailwind CSS for modern styling.
  - Chart.js for graphical insights and data visualization.
- **Backend**: PHP with PDO for secure database interaction.
- **Database**: MySQL for data storage and management.
- **Server**: Apache (XAMPP) for local development and deployment.

## Project Structure

```plaintext
Chittagong-University-Lost-and-Found-System/
├── assets/               # Tailwind CSS and image assets
├── includes/             # Common PHP includes (e.g., header, footer)
├── schema/               # Database schema for project setup
├── templates/            # Modular templates for consistent design
├── create_post.php       # For reporting lost/found items
├── edit_post.php         # Edit existing reports
├── delete_post.php       # Delete reported items
├── posts.php             # View all lost/found items
├── post_details.php      # Detailed view of a specific item
├── my_profile.php        # User profile management
├── signup.php            # User registration
├── login.php             # User login
├── index.php             # Entry point for the application
└── README.md             # Project documentation
```

## Setup Instructions
1. Clone the Repository
bash
Copy code
git clone https://github.com/EmranHoque/DatabaseProject-Chittagong-University-Lost-and-Found-System.git
2. Configure the Database
Create a new MySQL database.
Import the SQL schema located in the /schema directory.
Update the database connection details in db/db.php to match your setup.
3. Set Up the Server
Install and configure Apache, PHP, and MySQL.
Place the project files in your web server’s root directory (e.g., htdocs for XAMPP).
4. Run the Application
Start your Apache and MySQL server.
Access the application via your browser by navigating to http://localhost/index.php.
Usage Instructions
For Users:
Sign Up:
Create an account to access the platform.

Log In:
Sign in with your credentials.

Post an Item:

Report a lost or found item by filling out the form.
Include a detailed description, location, category, and optional image.
Browse Items:

View all reported items.
Filter items by category or location.
Interact:

Comment on posts to share information or inquire about items.
Profile Management:

Update your profile and manage your reported items.
Analytics Dashboard:
View summarized data about the system:
A pie chart for resolved/unresolved post ratios.
A list of the top 5 most-reported locations.
Future Enhancements
Add email notifications for updates on posts.
Allow users to mark items as resolved directly from their dashboards.
Implement a mobile-friendly design for better accessibility.
License
This project is licensed under the MIT License. See the LICENSE file for more details.
