# CRUD API with PHP Native

This is a simple CRUD API built with PHP native. It allows you to manage `program` records in a MySQL database.

## Features
- **Create**: Add a new program.
- **Read**: Retrieve all programs or a specific program by ID.
- **Update**: Modify an existing program.
- **Delete**: Remove a program.

## Setup

1. **Clone the repository**:
    ```bash
    git clone git@github.com:farahdilaup/program-crud-php-native.git
    ```

2. **Create the database** (`program_db`):
    ```sql
    CREATE TABLE program (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(10) NOT NULL,
        name VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        duration VARCHAR(50) NOT NULL
    );
    ```

3. **Configure the database connection** in `db.php`.

4. **Running the server**:
    ```bash
    localhost
    ```

## API Endpoints

### 1. Get all Programs:
- `GET /api/programs`

### 2. Get Program by ID:
- `GET /api/programs/?id={id}`

### 3. Create a Program:
- `POST /api/programs`
  <br>
    ```json
    {
        "name": "Program Name",
        "description": "Description",
        "duration": "Duration"
    }
    ```

### 4. Update a Program:
- `PUT /api/programs/?id={id}`
-   <br>
    ```json
    {
        "name": "Updated Name",
        "description": "Updated Description",
        "duration": "Updated Duration"
    }
    ```

### 5. Delete a Program:
- `DELETE /api/programs/?id={id}`

## License
This project is licensed under the MIT License.

---

Best regards,  
**farahdilaa** 👩‍💻  
