<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/html_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/helper_functions.php";
start_session();

try {
   $code = (int)htmlspecialchars($_GET["code"]);
} catch (Exception $e) {
   header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request", true, 400);
   die();
}

// 100-599: HTTP response status codes
// 1000+:   Special response codes
$codeList = [400, 401, 403, 404, 405, 500, 501, 502, 503, 504, 1000];

if (!in_array($code, $codeList)) {
   header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request", true, 400);
   die();
}

$errorTitle = "Oops... Something went wrong";

switch ($code) {
   case 400:
      $message = "HTTP 400: Bad Request";
      break;
   case 401:
      $message = "HTTP 401: Unauthorized";
      break;
   case 403:
      $message = "HTTP 403: Forbidden";
      break;
   case 404:
      $message = "HTTP 404: Page Not Found";
      break;
   case 405:
      $message = "HTTP 405: Method Not Allowed";
      break;
   case 500:
      $message = "HTTP 500: Internal Server Error";
      break;
   case 501:
      $message = "HTTP 501: Not Implemented";
      break;
   case 502:
      $message = "HTTP 502: Bad Gateway";
      break;
   case 503:
      $message = "HTTP 503: Service Unavailable";
      break;
   case 504:
      $message = "HTTP 504: Gateway Timeout";
      break;
   case 1000:
      $message = "PDOException - Contact the website owner if the problem persists";
      break;
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <title>Notification | Routerploit</title>
  <?php
  get_meta();
  get_favicon();
  get_css();
  get_js("notification");
  ?>
</head>


<body>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/header.php"; ?>

  <main>

    <section id="notification" class="block">
      <div class="container">

        <div class="main_l presentation">
          <h1 class="underline" data-aos="fade-right" data-aos-anchor-placement="top-bottom" data-aos-offset="150">
            <?php echo $errorTitle ?>
          </h1>
          <p class="notification_anchor" data-aos="fade" data-aos-anchor-placement="top-bottom" data-aos-offset="150">
            <?php echo $message ?>
          </p>
        </div>

        <div class="side_r icon min_laptop">
          <i class="fa-regular fa-bell" data-aos="fade-up" data-aos-anchor=".notification_anchor" data-aos-offset="150"></i>
        </div>

      </div>
    </section>

  </main>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/footer.php"; ?>

  <script>
      //window.setTimeout("location.href='/'", 7000);
  </script>

</body>

</html>