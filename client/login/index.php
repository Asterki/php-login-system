<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: ../home/");
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

    // Check if password is empty
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

                            header('location: ../home/');
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
      <meta charset="UTF-8">
      <link rel="icon" type="image/png" href="../../server/assets/icon.png">
      <title>Login</title>
      <link rel="stylesheet" type="text/css" href="../../server/css/pages/login.css">
   </head>
   <body>
      <main>
         <section class="container wrapper credentials centered">
            <h2 class="text-center"><b>Login</b></h2><br>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
               <div class="form-group <?php (!empty($username_err)) ? 'has_error' : '';?>">
                  <label for="username">Username</label>
                  <input type="text" name="username" id="username" class="form-control" autocomplete value="<?php echo $username ?>">
                  <span class="help-block error"><?php echo $username_err; ?></span>
               </div>
               <div class="form-group <?php (!empty($password_err)) ? 'has_error' : '';?>">
                  <label for="password">Password</label>
                  <input type="password" name="password" id="password" class="form-control" autocomplete value="<?php echo $password ?>">
                  <span class="help-block error"><?php echo $password_err; ?></span>
               </div>
               <div class="form-group"><br>
                  <input type="submit" class="btn btn-block login-button" value="login">
               </div>
               <p class="register">Don't have an account? <a href="../register/">Sign up</a>.</p>
            </form>
         </section>
      </main>
   </body>
</html>

