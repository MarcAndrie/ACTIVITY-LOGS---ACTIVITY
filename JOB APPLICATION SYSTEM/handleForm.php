<?php
session_start();  // Start the session

require_once 'models.php';  // Include the necessary functions

// Check if the form is being submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = null;
    
    // Check for add action
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        // Get the values from the form
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $qualification = $_POST['qualification'];
        $experience = $_POST['experience'];

        // Call the createApplicant function to insert a new applicant
        $result = createApplicant($full_name, $email, $phone, $qualification, $experience);
    }
    // Check for update action
    elseif (isset($_POST['action']) && $_POST['action'] === 'update') {
        // Get the values from the form
        $id = $_POST['id'];
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $qualification = $_POST['qualification'];
        $experience = $_POST['experience'];

        // Call the updateApplicant function to update an existing applicant
        $result = updateApplicant($id, $full_name, $email, $phone, $qualification, $experience);
    }
    // Check for delete action
    elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        // Get the applicant ID to delete
        $id = $_POST['id'];

        // Call the deleteApplicant function to delete the applicant
        $result = deleteApplicant($id);
    }

    // Store the result message in session
    if ($result) {
        $_SESSION['message'] = $result['message'];
        $_SESSION['statusCode'] = $result['statusCode'];
    }

    // Redirect back to index page
    header('Location: index.php');
    exit;
} else {
    echo "Invalid request.";
}
?>
