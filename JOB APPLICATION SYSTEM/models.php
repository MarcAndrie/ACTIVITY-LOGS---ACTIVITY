<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'dbConfig.php'; 

// Function to log activities
function logActivity($user_id, $activity_type, $activity_details) {
    global $pdo;

    // Debug: Check if user_id is set
    if (!isset($_SESSION['user_id'])) {
        error_log("No session user_id found!");
        return false;  // Return if there's no user_id in session
    }

    try {
        // Debug: Show what's being inserted
        error_log("Logging activity for user ID: " . $_SESSION['user_id']);
        error_log("Activity Type: " . $activity_type);
        error_log("Activity Details: " . $activity_details);

        $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, activity_type, activity_details) VALUES (:user_id, :activity_type, :activity_details)");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],  // Use session user_id
            'activity_type' => $activity_type,
            'activity_details' => $activity_details
        ]);

        return true;
    } catch (PDOException $e) {
        // Log the error or handle accordingly
        error_log("Error logging activity: " . $e->getMessage());
        return false;
    }
}
// User registration
function registerUser($username, $password) {
    global $pdo;
    try {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute([
            'username' => $username,
            'password' => $hashed_password
        ]);
        return [
            'statusCode' => 200,
            'message' => 'User registered successfully'
        ];
    } catch (PDOException $e) {
        return [
            'statusCode' => 400,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

// User login
function loginUser($username, $password) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return [
                'statusCode' => 200,
                'message' => 'Login successful'
            ];
        } else {
            return [
                'statusCode' => 401,
                'message' => 'Invalid username or password'
            ];
        }
    } catch (PDOException $e) {
        return [
            'statusCode' => 400,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

function createApplicant($full_name, $email, $phone, $qualification, $experience) {
    global $pdo;
    session_start();
    try {
        $stmt = $pdo->prepare("INSERT INTO applicants (full_name, email, phone, qualification, experience) VALUES (:full_name, :email, :phone, :qualification, :experience)");
        $stmt->execute([
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'qualification' => $qualification,
            'experience' => $experience
        ]);

        // Log the activity
        logActivity($_SESSION['user_id'], 'insert', "Added applicant: $full_name");

        return [
            'statusCode' => 200,
            'message' => 'Applicant added successfully'
        ];
    } catch (PDOException $e) {
        return [
            'statusCode' => 400,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}
// Function to retrieve an applicant by their ID
function getApplicantById($id) {
    global $pdo;  // Assuming you're using PDO for database connection

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT * FROM applicants WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the result
    $applicant = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if applicant exists
    if ($applicant) {
        return [
            'statusCode' => 200,
            'querySet' => [$applicant]  // Wrap the result in an array
        ];
    } else {
        return [
            'statusCode' => 404,
            'querySet' => []  // Empty result
        ];
    }
}

function updateApplicant($id, $full_name, $email, $phone, $qualification, $experience) {
    global $pdo;
    session_start();
    try {
        $stmt = $pdo->prepare("UPDATE applicants SET full_name = :full_name, email = :email, phone = :phone, qualification = :qualification, experience = :experience WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'qualification' => $qualification,
            'experience' => $experience
        ]);

        // Log the activity
        logActivity($_SESSION['user_id'], 'update', "Updated applicant with ID: $id");

        return [
            'statusCode' => 200,
            'message' => 'Applicant updated successfully'
        ];
    } catch (PDOException $e) {
        return [
            'statusCode' => 400,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

function deleteApplicant($id) {
    global $pdo;
    session_start();
    try {
        $stmt = $pdo->prepare("DELETE FROM applicants WHERE id = :id");
        $stmt->execute(['id' => $id]);

        // Log the activity
        logActivity($_SESSION['user_id'], 'delete', "Deleted applicant with ID: $id");

        return [
            'statusCode' => 200,
            'message' => 'Applicant deleted successfully'
        ];
    } catch (PDOException $e) {
        return [
            'statusCode' => 400,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

function searchApplicants($searchTerm) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM applicants WHERE full_name LIKE :searchTerm OR email LIKE :searchTerm OR phone LIKE :searchTerm OR qualification LIKE :searchTerm OR experience LIKE :searchTerm");
        $stmt->execute(['searchTerm' => "%" . $searchTerm . "%"]);
        $applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Log the activity
        logActivity($_SESSION['user_id'], 'search', "Searched for: $searchTerm");

        return [
            'statusCode' => 200,
            'querySet' => $applicants
        ];
    } catch (PDOException $e) {
        return [
            'statusCode' => 400,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}
?>
