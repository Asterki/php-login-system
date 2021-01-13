<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: ../home");
    exit;
}

require_once "../../server/config.php";

$username     = $password     = '';
$username_err = $password_err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty(trim($_POST['username']))) {
        $username_err = 'Please enter username.';
    } else {
        $username = trim($_POST['username']);
    }

    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = 'SELECT id, username, password FROM users WHERE username = ?';

        if ($stmt = $mysql_db->prepare($sql)) {

            $param_username = $username;

            $stmt->bind_param('s', $param_username);

            if ($stmt->execute()) {

                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $username, $hashed_password);

                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {

                            session_start();

                            $_SESSION['loggedin'] = true;
                            $_SESSION['id']       = $id;
                            $_SESSION['username'] = $username;

                            header('location: ../home');
                        } else {
                            $password_err = 'Invalid password';
                        }
                    }
                } else {
                    $username_err = "Username does not exists.";
                }
            } else {
                echo "Oops! Something went wrong please try again";
            }
            $stmt->close();
        }

        $mysql_db->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
            <title>
                Sign in
            </title>
            <link href="../../server/css/pages/login.css" rel="stylesheet" type="text/css">
                <link href="../../server/assets/icon.png" rel="icon" type="image/png">
                </link>
            </link>
        </meta>
    </head>
    <body class="theme-light">
        <header>
            <p class="text-center">
                Please fill this form to login to your account.
            </p>
        </header>
        <section class="container wrapper credentials centered">
            <h3 class="text-center">Test Site</h3>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="form-group <?php (!empty($username_err)) ? 'has_error' : '';?>"><br>
                    <label for="username" class="text-center">
                        Username
                    </label>
                    <input class="form-control" id="username" name="username" required autocomplete type="text" value="<?php echo $username ?>">
                        <span class="help-block">
                            <?php echo $username_err; ?>
                        </span>
                    </input>
                </div>
                <div class="form-group <?php (!empty($password_err)) ? 'has_error' : '';?>"><br>
                    <label for="password">
                        Password
                    </label>
                    <input class="form-control" id="password" name="password" required autocomplete type="password" value="<?php echo $password ?>">
                        <span class="help-block">
                            <?php echo $password_err; ?>
                        </span>
                    </input>
                </div><br>
                <div class="form-group">
                    <input class="login-button" type="submit" value="Login">
                    </input>
                </div>
            </form>
        </section>
        <footer>
            <p>
                Don't have an account?
                <a href="../register">
                    Sign in
                </a>
                .
            </p>
        </footer>
    </body>
</html>