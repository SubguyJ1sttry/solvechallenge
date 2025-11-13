<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/html_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/helper_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/mysql_functions.php";
start_session();
requireLogin();

if (!master_login()) {
  if (
    (!isset($_GET["auth_code"]))
    || (!preg_match('/^AUTH::(?P<code>[0-9a-f]+)$/', $_GET["auth_code"], $matches))
    || ($matches["code"] != get_auth_code($auth_code_seed))
  ) {
    goto_page("/");
  }
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <title>Business requests | Routerploit</title>
  <?php
  get_meta();
  get_favicon();
  get_css();
  get_js("business_requests");
  ?>
</head>


<body>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/header.php"; ?>

  <main>

    <section id="invoices" class="block">
      <div class="container">
        <div class="formtext">
          <div class="input_group">
            <h2>Invoices | Business requests</h2>
          </div>

          <?php
          $invoices = get_request_invoices();
          foreach ($invoices as $invoice):
            $product = get_product_data($invoice["product_guid"]); ?>
            <div class="invoices">
              <p class="product_name"><?php echo $invoice["purchase_date"] . ": " . $product["name"]; ?></p>
              <p class="invoice_title"><?php echo $invoice['guid']; ?></p>
              <?php if (!empty($invoice["request"])) { ?><p class="invoice_request">Request: <?php echo $invoice["request"]; ?></p><?php } ?>
              <a href="/invoice_details.php?guid=<?php echo $invoice['guid']; ?>" target="_blank"></a>
            </div>
          <?php endforeach; ?>

        </div>
      </div>
    </section>

  </main>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/footer.php"; ?>

</body>

</html>