<?php
require_once 'models.php';

// Get the applicant's data for editing
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "No applicant ID provided!";
    exit;
}

$applicantId = $_GET['id'];
$applicantData = getApplicantById($applicantId);

if ($applicantData['statusCode'] !== 200 || empty($applicantData['querySet'])) {
    echo "Applicant not found!";
    exit;
}

$applicant = $applicantData['querySet'][0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Applicant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 1em 2em;
        }
        main {
            padding: 2em;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #343a40;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="email"], input[type="number"], textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Applicant</h1>
    </header>
    <main>
        <div class="container">
            <h2>Update Applicant Details</h2>
            <form method="POST" action="handleForm.php">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= $applicant['id'] ?>">
                
                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($applicant['full_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($applicant['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($applicant['phone']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="qualification">Qualification:</label>
                    <textarea name="qualification" required><?= htmlspecialchars($applicant['qualification']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="experience">Experience (years):</label>
                    <input type="number" name="experience" value="<?= htmlspecialchars($applicant['experience']) ?>" required>
                </div>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </main>
</body>
</html>
