<?php
include 'components/connect.php';
// Include your database connection or connection code here

if(isset($_POST['report_user'])) {
    $property_id = $_POST['property_id'];

    echo "working";
    // echo $user_id;

    // $property_id = $_POST['property_id']; // Get the property ID being reported
    $user_id = $_POST['user_id']; // Set the user ID of the admin or the logged-in user who is reporting
    echo $user_id;

    // Example: Insert the report into the 'reported_users' table
    // $insert_report = $conn->prepare("INSERT INTO reported_users (user_id, property_id) VALUES (?, ?)");
    // $insert_report->execute([$user_id, $property_id]);



    // Check if the report already exists
    $check_report = $conn->prepare("SELECT * FROM reported_users WHERE user_id = ?");
    $check_report->execute([$user_id]);

    if ($check_report->rowCount() == 0) {
        // Insert a new report into the reported_properties table
        $insert_report = $conn->prepare("INSERT INTO reported_users (user_id) VALUES (?)");
        $insert_report->execute([$user_id]);
        // Add a success message
        $success_msg = 'User reported successfully!';
        echo "<script>alert('$success_msg');</script>";
    } else {
        // Add a warning message that the property is already reported
        $warning_msg[] = 'This user is already reported!';
        echo "<script>alert('$warning_msg');</script>";
        
    }
    // confirm("ok?");
    
}

// Redirect back to the view property page
header('location:view_property.php?property_id=' . $property_id);