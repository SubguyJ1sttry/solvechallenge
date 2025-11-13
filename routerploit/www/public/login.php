<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/html_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/helper_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/mysql_functions.php";
start_session();

requireLogout();

$error = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $error_msg = login();
  $error = !empty($error_msg);
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <title>Login | Routerploit</title>
  <?php
  get_meta();
  get_favicon();
  get_css();
  get_js("login");
  ?>
</head>


<body>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/header.php"; ?>

  <main>

    <section id="login" class="block">
      <div class="container">
        
        <form class="form" action="/login.php" method="post">
          <h1>Login</h1>
          <?php if ($error) {?>
          <div class="input_group">
            <label class="error"><?php echo $error_msg; ?></label>
          </div>
          <?php } ?>
          <div class="input_group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="" required>
          </div>
          <div class="input_group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="" required>
          </div>
          <input type="submit" value="Login">
          <div class="input_group">
            Don't have an account?
            <a href="/signup.php">Sign up</a>
          </div>
        </form>

      </div>
    </section>

  </main>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/footer.php"; ?>

</body>

</html>