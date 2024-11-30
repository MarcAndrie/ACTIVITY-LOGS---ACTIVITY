<?php
require_once 'models.php';

if (!isset($_GET['id'])) {
    die('Applicant ID is required.');
}

$id = $_GET['id'];
$response = deleteApplicant($id);

if ($response['statusCode'] === 200) {
    header('Location: index.php?message=' . urlencode($response['message']));
    exit;
} else {
    die('Error: ' . $response['message']);
}
?>
