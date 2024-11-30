<?php
require 'includes/db.php';

session_start();
include 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Email sanitization
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Secure the session
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['name'] = $user['name'];

                header('Location: index.php');
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            // Log the database error
            error_log($e->getMessage(), 3, 'errors.log');
            $error = "An error occurred. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication - Chittagong University Lost & Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-200">
   

    <!-- Authentication Section -->
    <div class="max-w-md mx-auto py-12 px-4">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6 text-center">Login to your Account</h1>
        <form action="login.php" method="POST" class="bg-white p-8 rounded-lg shadow-md">
            <!-- Error Message -->
            <?php if (isset($error)): ?>
                <div class="mb-4 text-red-500 text-sm">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center items-center">
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:opacity-90 transition duration-300">Log In</button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">Don't have an account? <a href="signup.php" class="text-purple-600 hover:underline">Sign Up</a></p>
        </div>
    </div>
</body>
</html>

<?php include 'templates/footer.php'; ?>
