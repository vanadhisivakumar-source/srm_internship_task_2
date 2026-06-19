<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div id="forgotPassword" class="auth-container view-section hidden">
        <div class="auth-card text-center">
            <div class="lock-icon-wrapper">
                <i class="fa-solid fa-lock-open custom-lock"></i>
            </div>
            <h2>Forgot Password</h2>
            <form onsubmit="validateForgotPassword(event)">
                <div class="form-group text-left">
                    <label>Email</label>
                    <input type="email" id="forgotEmail" name="email" placeholder="Enter your email" required>
                    <label>New Password</label>
                    <input type="password" id="newPassword" name="password" placeholder="Enter New Password" required>
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('loginPass')"></i>
                </div>
                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
            <div class="auth-footer">
                <a href="login.php" class="btn btn-secondary">Back to Login</a>
            </div>
        </div>
    </div>
</body>
    <script src="script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('forgotPassword').classList.remove('hidden');
        });
    </script>
</html>