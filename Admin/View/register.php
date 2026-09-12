<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>G7CS - Create Account</title>
<link rel="stylesheet" href="../CSS/admin.css">
<link rel="stylesheet" href="../CSS/register.css">
</head>
<body class="login-page">
<div class="login-card register-card">
    <h1>Create Account</h1>
    <p>Sign up to start shopping with G7CS</p>
    <form id="registerForm">
        <label for="name">Full Name</label>
        <input id="name" name="name" type="text" required>

        <label for="email">Email</label>
        <input id="email" name="email" type="email" required>

        <label for="phone">Phone</label>
        <input id="phone" name="phone" type="tel">

        <label for="address">Address</label>
        <input id="address" name="address" type="text">

        <label for="password">Password</label>
        <input id="password" name="password" type="password" minlength="8" required>

        <label for="confirm_password">Confirm Password</label>
        <input id="confirm_password" name="confirm_password" type="password" minlength="8" required>

        <button type="submit">Register</button>
        <div id="message" class="message"></div>
    </form>
    <p class="switch-link">Already have an account? <a href="login.php">Sign in</a></p>
</div>
<script src="../JS/register.js"></script>
</body>
</html>
