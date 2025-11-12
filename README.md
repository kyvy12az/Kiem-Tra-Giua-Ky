# PHP CRUD Application

This project is a simple PHP CRUD (Create, Read, Update, Delete) application that allows users to manage records in a database using a procedural approach. The application is structured to provide a clear separation of concerns while maintaining simplicity.

![alt text](https://i.ibb.co/fVQSK7Nw/sql.png)

## Project Structure

```
php-crud-procedural
├── public
│   ├── index.php        # Main entry point of the application
│   ├── add.php          # Form for adding new records
│   ├── edit.php         # Form for editing existing records
│   ├── delete.php       # Handles record deletion
│   ├── view.php         # Displays details of a specific record
│   └── assets
│       ├── css
│       │   └── style.css # CSS styles for the application
│       └── js
│           └── main.js   # JavaScript for client-side functionality
├── includes
│   ├── db.php           # Database connection
│   ├── config.php       # Configuration settings
│   ├── functions.php     # Utility functions for CRUD operations
│   ├── header.php       # HTML header section
│   └── footer.php       # HTML footer section
├── migrations
│   └── schema.sql       # SQL statements for database structure
├── tests
│   └── UserCrudTest.php  # Test cases for CRUD operations
├── .env.example         # Example environment variables
├── composer.json        # Composer configuration file
└── README.md            # Project documentation
```

## Setup Instructions

1. **Clone the Repository**: Clone this repository to your local machine.
   
   ```bash
   git clone <repository-url>
   ```

2. **Install Dependencies**: Navigate to the project directory and install the required dependencies using Composer.

   ```bash
   cd php-crud-procedural
   composer install
   ```

3. **Configure Database**: Update the `includes/config.php` file with your database credentials.

4. **Run Migrations**: Execute the SQL statements in `migrations/schema.sql` to create the necessary database tables.

5. **Access the Application**: Open your web browser and navigate to `http://localhost/php-crud-procedural/public/index.php` to access the application.

## Usage

- **Add Records**: Navigate to `add.php` to add new records to the database.
- **Edit Records**: Use `edit.php` to modify existing records.
- **Delete Records**: Access `delete.php` to remove records from the database.
- **View Records**: Use `view.php` to see the details of a specific record.

## Testing

Run the test cases in `tests/UserCrudTest.php` to ensure that all CRUD operations work as expected.

## License

This project is open-source and available under the MIT License.
