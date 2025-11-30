<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: adminPage/admin.php");
    } else {
        header("Location: userPage/user.php");
    }
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supermarket";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if (isset($_POST['signupSubmit'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['pass'];
    $role = $_POST['role'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO user (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        $message = "<div class='success-msg'>Registration successful! Please log in.</div>";
    } else {
        if ($conn->errno == 1062) {
            $message = "<div class='error-msg'>Username or email already exists.</div>";
        } else {
            $message = "<div class='error-msg'>Error during registration: " . $stmt->error . "</div>";
        }
    }
    $stmt->close();
}

if (isset($_POST['loginSubmit'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['pass'];

    $stmt = $conn->prepare("SELECT user_id, username, password, role FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: adminPage/admin.php");
            } else {
                header("Location: userPage/user.php");
            }
            exit();
        } else {
            $message = "<div class='error-msg'>Invalid username or password.</div>";
        }
    } else {
        $message = "<div class='error-msg'>Invalid username or password.</div>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALL - IN - ONE - Login/Sign Up</title>
    <link rel="icon" type="image/x-icon" href="img/icon.ico">
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <div class="auth-wrapper">
        <h1 class="project-title">ALL - IN - ONE</h1>
        <?php echo $message; ?>
        <form class="auth-form loginForm" method="post" action="index.php">
            <h2 class="formHeader">Login</h2>
            <div class="inputGroup"><label for="login-username">Username</label>
                <input name="username" id="login-username" type="text" placeholder="Your Username" required>
            </div>
            <div class="inputGroup"><label for="login-pass">Password</label>
                <input name="pass" id="login-pass" type="password" minlength="8" placeholder="Password (min 8 chars)"
                    required>
            </div>
            <div class="buttons">
                <button name="loginSubmit" type="submit">Login</button>
            </div>
            <p class="toggle-text">I don't have an account |
                <a id="toSignUp" onclick="toSignUp()">Sign Up</a>
            </p>
        </form>
        <form class="auth-form signUpForm hidden" method="post" action="index.php">
            <h2 class="formHeader">Sign Up</h2>
            <div class="inputGroup"><label for="signup-username">Username</label>
                <input name="username" id="signup-username" type="text" placeholder="Choose a Username" required>
            </div>
            <div class="inputGroup"><label for="signup-email">Email</label>
                <input name="email" id="signup-email" type="email" placeholder="Email address" required>
            </div>
            <div class="inputGroup"><label for="signup-pass">Password</label>
                <input name="pass" id="signup-pass" type="password" minlength="8" placeholder="Password (min 8 chars)"
                    required>
            </div>
            <div class="inputGroup"><label for="signup-pass">Role</label>
                <select name="role" id="signup-role" required>
                    <option value="user" selected>User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="buttons">
                <button name="signupSubmit" type="submit">Sign Up</button>
            </div>
            <p class="toggle-text">I Already have an account |
                <a id="toLogin" onclick="toLogin()">Login</a>
            </p>
        </form>
    </div>

    <script src="index.js"></script>
</body>

</html>