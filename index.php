<?php
// Include the config file
require_once 'config.php';

// Initialize variables
$name = $email = $phone = '';
$id = 0;
$edit_state = false;

// Create database connection
$conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Handle form submission
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Insert data into the database
    $query = "INSERT INTO users (name, email, phone) VALUES ('$name', '$email', '$phone')";
    mysqli_query($conn, $query);
    header('location: index.php');
    exit();
}

// Update records
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Update data in the database
    $query = "UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id=$id";
    mysqli_query($conn, $query);
    header('location: index.php');
    exit();
}

// Delete records
if (isset($_GET['del'])) {
    $id = $_GET['del'];

    // Delete data from the database
    $query = "DELETE FROM users WHERE id=$id";
    mysqli_query($conn, $query);
    header('location: index.php');
    exit();
}

// Retrieve records from the database
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_state = true;

    // Retrieve the user record to be edited
    $query = "SELECT * FROM users WHERE id=$id";
    $edit_result = mysqli_query($conn, $query);
    $edit_row = mysqli_fetch_assoc($edit_result);

    // Set the form fields to the values of the selected user record
    $name = $edit_row['name'];
    $email = $edit_row['email'];
    $phone = $edit_row['phone'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Demo Project</title> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td, table th {
            padding: 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
<div class="container">
    <form method="post" action="index.php">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div>
            <label>Name:</label>
            <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
        </div>
        <div>
            <label>Email:</label>
            <input type="email" class="form-control" name="email" value="<?php echo $email; ?>">
        </div>
        <div>
            <label>Phone:</label>
            <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
        </div><br>
        <div>
            <?php if ($edit_state === false): ?>
                <button type="submit" class="btn btn-default" name="save">Save</button>
            <?php else: ?>
                <button type="submit" class="btn btn-default" name="update">Update</button>
            <?php endif ?>
        </div>
    </form>
    <br><br>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td>
                        <a href="index.php?edit=<?php echo $row['id']; ?>">Edit</a>
                    </td>
                    <td>
                        <a href="index.php?del=<?php echo $row['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
