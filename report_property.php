<?php
include 'components/connect.php';

if(isset($_POST['report'])){
    $property_id = $_POST['property_id'];
    $user_id = $_COOKIE['user_id']; // Adjust based on your authentication method

    // Check if the report already exists
    $check_report = $conn->prepare("SELECT * FROM reported_properties WHERE property_id = ? AND user_id = ?");
    $check_report->execute([$property_id, $user_id]);

    if ($check_report->rowCount() == 0) {
        // Insert a new report into the reported_properties table
        $insert_report = $conn->prepare("INSERT INTO reported_properties (property_id, user_id) VALUES (?, ?)");
        $insert_report->execute([$property_id, $user_id]);
        // Add a success message
        $success_msg[] = 'Property reported successfully!';
    } else {
        // Add a warning message that the property is already reported
        $warning_msg[] = 'You have already reported this property!';
    }
}

// Redirect back to the view property page
header('location:view_property.php?property_id=' . $property_id);
