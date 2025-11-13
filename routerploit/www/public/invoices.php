<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/html_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/helper_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/mysql_functions.php";
start_session();

requireLogin();

$user_guid = get_user_guid();
$user = get_user_data($user_guid);

validate_guid($user_guid, "/account.php");
require_user_exists($user_guid);
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <title>Invoices | Routerploit</title>
  <?php
  get_meta();
  get_favicon();
  get_css();
  get_js("invoices");
  ?>
</head>


<body>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/header.php"; ?>

  <main>

    <section id="invoices" class="block">
      <div class="container">
        <div class="formtext">
          <div class="input_group">
            <h2>Your Invoices</h2>
          </div>

          <?php
          [$invoices, $count_gt_limit] = get_invoices($user_guid, $user["account_type"], null);
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