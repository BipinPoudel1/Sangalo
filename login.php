<?php
session_start();

// Define admin credentials
$admin_email = 'admin@digitalsangalo.com';
$admin_password = 'digitalsangalo12';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate credentials
    if ($email == $admin_email && $password == $admin_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['is_admin'] = true; // Set admin flag
        header('Location: admin_landing.php');
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            background: #f0f0f0;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
            text-align: center;
        }
        .input-container {
            position: relative;
            margin-bottom: 20px;
        }
        .input-container input {
            width: 100%;
            padding: 10px 5px;
            border: none;
            border-bottom: 1px solid #ccc;
            outline: none;
            font-size: 16px;
        }
        .input-container label {
            position: absolute;
            top: 10px;
            left: 5px;
            color: #aaa;
            transition: all 0.3s ease;
            pointer-events: none;
        }
        .input-container input:focus + label,
        .input-container input:not(:placeholder-shown) + label {
            top: -15px;
            left: 0;
            color: #333;
            font-size: 12px;
        }
        .login-button {
            width: 100%;
            padding: 10px;
            border: 1px solid #333;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 16px;
        }
        .login-button:hover {
            background: transparent;
            color: #333;
            border-color: #007BFF;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="post" action="">
            <div class="input-container">
                <input type="text" name="email" required placeholder=" ">
                <label>Email</label>
            </div>
            <div class="input-container">
                <input type="password" name="password" required placeholder=" ">
                <label>Password</label>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
