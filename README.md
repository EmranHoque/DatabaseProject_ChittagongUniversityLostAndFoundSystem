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
