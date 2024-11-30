<?php
require_once 'models.php';

$searchResults = [];
$searching = false;

  // Start the session to access user data

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the logged-in user's ID and name
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Check if there's a message to display
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';  // Retrieve message from session
$status = isset($_SESSION['statusCode']) ? $_SESSION['statusCode'] : '';  // Retrieve status from session

// Clear the message from session after displaying
unset($_SESSION['message']);
unset($_SESSION['statusCode']);

// Handle search functionality
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searching = true;
    $searchTerm = trim($_GET['search']);
    logActivity($user_id, 'search', "Search term: $searchTerm");  // Log the search activity
    $searchResults = searchApplicants($searchTerm);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application System</title>
    <style>
        /* Styles unchanged */
    </style>
</head>
<body>
    <header>
        <h1>Teacher Job Application System</h1>
        <p>Welcome, <?= htmlspecialchars($username) ?> | <a href="logout.php">Logout</a></p>
    </header>
    <main>
        <div class="container">
            <!-- Display Messages -->
            <?php if ($message): ?>
                <div class="message <?= $status === 200 ? 'message-success' : 'message-error' ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <!-- Add Applicant Form -->
            <h2>Add New Applicant</h2>
            <form action="handleForm.php" method="POST">
                <input type="hidden" name="action" value="add" />
                <label for="full_name">Full Name:</label>
                <input type="text" name="full_name" required />

                <label for="email">Email:</label>
                <input type="email" name="email" required />

                <label for="phone">Phone:</label>
                <input type="text" name="phone" required />

                <label for="qualification">Qualification:</label>
                <input type="text" name="qualification" required />

                <label for="experience">Experience:</label>
                <input type="text" name="experience" required />

                <button type="submit">Add Applicant</button>
            </form>

            <!-- Search Form -->
            <h2>Search Applicants</h2>
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search by name, email, phone, qualification, or experience" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit">Search</button>
            </form>

            <!-- Applicants Table -->
            <?php if ($searching): ?>
            <?php if ($searchResults['statusCode'] === 200 && count($searchResults['querySet']) > 0): ?>
            <h2>Search Results</h2>
            <table>
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Qualification</th>
                        <th>Experience</th>
                        <th>Actions</th>
                     </tr>
                </thead>
                <tbody>
                    <?php foreach ($searchResults['querySet'] as $applicant): ?>
                    <tr>
                        <td><?= htmlspecialchars($applicant['full_name']) ?></td>
                        <td><?= htmlspecialchars($applicant['email']) ?></td>
                        <td><?= htmlspecialchars($applicant['phone']) ?></td>
                        <td><?= htmlspecialchars($applicant['qualification']) ?></td>
                        <td><?= htmlspecialchars($applicant['experience']) ?> years</td>
                        <td>
                            <a href="editApplicant.php?id=<?= $applicant['id'] ?>">Edit</a>
                            <form method="POST" action="handleForm.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $applicant['id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this applicant?');">Delete</button>
                             </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="message message-error">No applicants found for the given search.</div>
        <?php endif; ?>
    <?php endif; ?>
        </div>
    </main>
</body>
</html>
