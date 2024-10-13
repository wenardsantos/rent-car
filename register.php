<?php

include 'connect_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
  $role = $_POST['role'];

  // Secure and fast way of querying to the database. Prepared statement 
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  // Replacing the ? with the variable attached to bind_param();
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    echo "User exist already!";
  } else {

    $stmt = $conn->prepare("INSERT INTO users(username, email, password, role) VALUES (?, ?, ?, ?) ");
    $stmt->bind_param('ssss', $username, $email, $password, $role);
    
    if ($stmt->execute()) {
      echo "NEW RECORD CREATED";
      header('Location: index.html');
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }
}
?>
