<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/html_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/helper_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/mysql_functions.php";
start_session();

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

requireLogout();

$error = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $error_msg = signup();
  $error = !empty($error_msg);

  if (!$error) {
    goto_page("/login.php");
  }
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <title>Sign up | Routerploit</title>
  <?php
  get_meta();
  get_favicon();
  get_css();
  get_js("signup");
  ?>
</head>


<body>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/header.php"; ?>

  <main>

    <section id="signup" class="block">
      <div class="container">
        
        <form class="form" action="/signup.php" method="post">
          <h1>Sign up</h1>
          <?php if ($error) {?>
          <div class="input_group">
            <label class="error"><?php echo $error_msg; ?></label>
          </div>
          <?php } ?>
          <div class="input_group">
            <label for="first_name">First name</label>
            <input type="text" name="first_name" id="first_name" placeholder="" value="<?php echo $_POST["first_name"]; ?>" required>
          </div>
          <div class="input_group">
            <label for="last_name">Last name</label>
            <input type="text" name="last_name" id="last_name" placeholder="" value="<?php echo $_POST["last_name"]; ?>" required>
          </div>
          <div class="input_group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="" value="<?php echo $_POST["username"]; ?>" required>
          </div>
          <div class="input_group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="" value="<?php echo $_POST["password"]; ?>" required>
          </div>
          <div class="input_group">
            <label for="account_type" class="margin_bottom_xs">Account type</label>
            <div class="radio_wrapper">
              <div class="radio">
                <input type="radio" name="account_type" id="personal" value="personal" checked required>
                <label for="personal" class="radio">Personal</label>
              </div>
              <div class="radio">
                <input type="radio" name="account_type" id="business" value="business" required>
                <label for="business" class="radio">Business</label>
              </div>
            </div>
          </div>
          <input type="submit" value="Sign up">
          <div class="input_group">
            Already have an account?
            <a href="/login.php">Login</a>
          </div>
        </form>

      </div>
    </section>

  </main>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/footer.php"; ?>

</body>

</html>