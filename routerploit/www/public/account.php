<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/html_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/helper_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/mysql_functions.php";
start_session();

requireLogin();

$user_guid = get_user_guid();

validate_guid($user_guid, "/account.php");
require_user_exists($user_guid);

$user = get_user_data($user_guid);
$address = get_user_address($user_guid);

$error = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["mode"] == "save") {
  $error_location = "account_details";
  $error_msg = save_changes();
  $error = !empty($error_msg);

  if (!$error) {
    goto_page("/account.php");
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["mode"] == "pin_note") {
  $error_location = "note";
  $error_msg = save_note();
  $error = !empty($error_msg);

  if (!$error) {
    goto_page("/account.php");
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["mode"] == "invoice_request") {
  $error_location = "invoice_request";
  if ($user["account_type"] !== "business") {
    $error_msg = "Invoice request are only available for our business customers.";
  }
  else {
    $error_msg = create_invoice_request();
  }

  $error = !empty($error_msg);

  if (!$error) {
    goto_page("/account.php");
  }
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <title>Account | Routerploit</title>
  <?php
  get_meta();
  get_favicon();
  get_css();
  get_js("account");
  ?>
</head>


<body>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/header.php"; ?>

  <main>

    <section id="account_details" class="block">
      <div class="container">

        <?php if (isset($_GET["mode"]) && $_GET["mode"] == "edit") { ?>

          <form class="form" action="/account.php?mode=save" method="post">
            <h1><?php echo $user["first_name"] . " " . $user["last_name"]; ?></h1>
            <div class="input_group">
              <label for="account_type">Account type</label>
              <input type="text" name="account_type" id="account_type" placeholder="" value="<?php echo $user["account_type"]; ?>" required>
            </div>
            <div class="input_group">
              <label for="username">Username</label>
              <input type="text" name="username" id="username" placeholder="" value="<?php echo $user["username"]; ?>" required>
            </div>
            <div class="input_group">
              <label for="first_name">First name</label>
              <input type="text" name="first_name" id="first_name" placeholder="" value="<?php echo $user["first_name"]; ?>" required>
            </div>
            <div class="input_group">
              <label for="last_name">Last name</label>
              <input type="text" name="last_name" id="last_name" placeholder="" value="<?php echo $user["last_name"]; ?>" required>
            </div>
            <div class="input_group">
              <input type="text" name="user_guid" id="user_guid_account" placeholder="" value="<?php echo $_SESSION['user_guid']; ?>">
            </div>
            <div class="input_group">
              <label for="street">Street</label>
              <input type="text" name="street" id="street" placeholder="" value="<?php echo $address["street"]; ?>">
            </div>
            <div class="input_group">
              <label for="postal_code">Postal code</label>
              <input type="text" name="postal_code" id="postal_code" placeholder="" value="<?php echo $address["postal_code"]; ?>">
            </div>
            <div class="input_group">
              <label for="city">City</label>
              <input type="text" name="city" id="city" placeholder="" value="<?php echo $address["city"]; ?>">
            </div>
            <div class="input_group">
              <label for="country">Country</label>
              <input type="text" name="country" id="country" placeholder="" value="<?php echo $address["country"]; ?>">
            </div>
            <input type="submit" value="Save">
            <?php if ($user_guid == $_SESSION["user_guid"]) { ?>
              <div class="input_group">
                <a href="/account.php" class="clr_blue_dark">Discard changes</a>
              </div>
            <?php } ?>
          </form>

        <?php } else { ?>

          <form class="form">
            <h1><?php echo $user["first_name"] . " " . $user["last_name"]; ?></h1>
            <?php if ($error && $error_location == "account_details") { ?>
              <div class="input_group">
                <label class="error"><?php echo $error_msg; ?></label>
              </div>
            <?php } ?>
            <div class="input_group">
              <label>Account type</label>
              <input id="account_type" value="<?php echo $user["account_type"]; ?>" disabled>
            </div>
            <div class="input_group">
              <label>Username</label>
              <input id="username" value="<?php echo $user["username"]; ?>" disabled>
            </div>
            <div class="input_group">
              <label>First name</label>
              <input id="first_name" value="<?php echo $user["first_name"]; ?>" disabled>
            </div>
            <div class="input_group">
              <label>Last name</label>
              <input id="last_name" value="<?php echo $user["last_name"]; ?>" disabled>
            </div>
            <div class="input_group">
              <label>Street</label>
              <input id="street" value="<?php echo value_of($address["street"]); ?>" disabled>
            </div>
            <div class="input_group">
              <label>Postal code</label>
              <input id="postal_code" value="<?php echo value_of($address["postal_code"]); ?>" disabled>
            </div>
            <div class="input_group">
              <label>City</label>
              <input id="city" value="<?php echo value_of($address["city"]); ?>" disabled>
            </div>
            <div class="input_group">
              <label>Country</label>
              <input id="country" value="<?php echo value_of($address["country"]); ?>" disabled>
            </div>
            <div class="input_group">
              <a href="/account.php?mode=edit" class="clr_blue_dark">Edit details</a>
            </div>
          </form>

        <?php } ?>

      </div>
    </section>


    <section id="invoices" class="block">
      <div class="container">
        <div class="formtext">
          <div class="input_group">
            <h2>Your Invoices</h2>
          </div>

          <?php
          [$invoices, $count_gt_limit] = get_invoices($user_guid);
          foreach ($invoices as $invoice):
            $product = get_product_data($invoice["product_guid"]); ?>
            <div class="invoices">
              <p class="product_name"><?php echo $invoice["purchase_date"] . ": " . $product["name"]; ?></p>
              <p class="invoice_title"><?php echo $invoice['guid']; ?></p>
              <?php if (!empty($invoice["request"])) { ?><p class="invoice_request">Your request: <?php echo $invoice["request"]; ?></p><?php } ?>
              <a href="/invoice_details.php?guid=<?php echo $invoice['guid']; ?>" target="_blank"></a>
            </div>
          <?php endforeach;
          if ($count_gt_limit) { ?>
            <div class="invoices">
              <p class="invoice_title">Show all invoices</p>
              <a href="/invoices.php?user_guid=<?php echo $user_guid; ?>"></a>
            </div>
          <?php } ?>

        </div>
      </div>
    </section>


    <?php if ($user["account_type"] == "business") { ?>
      <section id="comment" class="block">
        <div class="container">

          <form class="form" action="/account.php?mode=invoice_request" method="post">
            <div class="input_group">
              <h4>For your business needs</h4>
              <h2>Create Invoice Requests</h2>
            </div>
            <?php if ($error && $error_location == "invoice_request") { ?>
              <div class="input_group">
                <label class="error"><?php echo $error_msg; ?></label>
              </div>
            <?php } ?>
            <div class="input_group">
              <label for="invoice_guid">Invoice</label>
              <select name="invoice_guid" id="invoice_guid" required>
                <option value="" selected disabled>Select an invoice</option>
                <?php [$invoices, $count_gt_limit] = get_invoices($user_guid, "", null, true);
                foreach ($invoices as $invoice): 
                  $product = get_product_data($invoice["product_guid"]);?>
                  <option value="<?php echo $invoice['guid']; ?>"><?php echo $invoice['guid'] . " [" . $product["name"] . "]"; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="input_group">
              <label for="request">Your request</label>
              <textarea name="request" id="request" placeholder="" required></textarea>
            </div>
            <div class="input_group">
              <input type="text" name="user_guid" id="user_guid_request" placeholder="" value="<?php echo $_SESSION['user_guid']; ?>">
            </div>
            <input type="submit" value="Submit" class="short">
          </form>

        </div>
      </section>
    <?php } ?>


    <section id="comments" class="block">
      <div class="container">
        <div class="formtext">
          <div class="input_group">
            <h2>Your Comments</h2>
          </div>

          <?php foreach (get_user_comments($user_guid) as $comment): ?>
            <?php $product_name = get_product_name($comment["product_guid"]) ?? null; ?>
            <div class="user_comment">
              <p class="product_name">Product: <?php echo $product_name; ?></p>
              <p class="comment_title"><?php echo $comment['title']; ?></p>
              <p class="comment"><?php echo nl2br($comment['comment']); ?></p>
              <a href="/product.php?guid=<?php echo $comment["product_guid"]; ?>"></a>
            </div>
          <?php endforeach; ?>

        </div>
      </div>
    </section>


    <section id="notes" class="block">
      <div class="container">
        <div class="formtext">
          <div class="input_group">
            <h2>Notes</h2>
          </div>

          <?php foreach (get_user_notes($user_guid) as $note): ?>
            <div class="user_note">
              <p class="note"><?php echo nl2br($note['note']); ?></p>
            </div>
          <?php endforeach; ?>

        </div>
      </div>
    </section>


    <section id="add_note" class="block">
      <div class="container">

        <form class="form" action="/account.php?mode=pin_note" method="post">
          <div class="input_group">
            <h2>Add notes</h2>
          </div>
          <?php if ($error && $error_location == "note") { ?>
            <div class="input_group">
              <label class="error"><?php echo $error_msg; ?></label>
            </div>
          <?php } ?>
          <div class="input_group">
            <textarea name="note" id="note" placeholder="Start writing..." required></textarea>
          </div>
          <div class="input_group">
            <input type="text" name="user_guid" id="user_guid_note" placeholder="" value="<?php echo $_SESSION['user_guid']; ?>">
          </div>
          <input type="submit" value="Pin note" class="short">
        </form>

      </div>
    </section>

  </main>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/footer.php"; ?>

</body>

</html>