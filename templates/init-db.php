<?php
$dbname = '/var/www/html/employees.db';

try {
    $pdo = new PDO("sqlite:$dbname");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create the employees table
    $sql = "CREATE TABLE IF NOT EXISTS employees (
        employee_id INTEGER PRIMARY KEY AUTOINCREMENT,
        first_name TEXT,
        last_name TEXT,
        department TEXT,
        job_title TEXT,
        email TEXT,
        phone_number TEXT,
        hire_date DATE,
        status TEXT
    )";
    $pdo->exec($sql);
    
    // Insert initial data
    $insert_sql = "INSERT INTO employees (first_name, last_name, department, job_title, email, phone_number, hire_date, status) VALUES
        ('John', 'Doe', 'IT', 'Software Engineer', 'john.doe@example.com', '1234567890', '2023-01-15', 'Active'),
        ('Jane', 'Smith', 'HR', 'HR Manager', 'jane.smith@example.com', '0987654321', '2023-01-20', 'Active'),
        ('Mark', 'Brown', 'Sales', 'Sales Representative', 'mark.brown@example.com', '1122334455', '2023-01-25', 'Inactive')";
    $pdo->exec($insert_sql);
    
    echo "SQLite database created with table 'employees' and initial data inserted.";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
