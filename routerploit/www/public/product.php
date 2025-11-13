<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/html_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/helper_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/mysql_functions.php";
start_session();

$product_guid = get_product_guid();
validate_guid($product_guid, "/");

$product = get_product_data($product_guid);

$error = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["mode"] == "submit") {
  $error_location = "comment";
  $error_msg = save_post();
  $error = !empty($error_msg);

  if (!$error) {
    goto_page("/product.php?guid=" . $product_guid);
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["mode"] == "purchase") {
  $error_location = "purchase";
  $error_msg = purchase();
  $error = !empty($error_msg);

  if (!$error) {
    goto_page("/product.php?guid=" . $product_guid);
  }
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <title><?php echo $product["name"]; ?> | Routerploit</title>
  <?php
  get_meta();
  get_favicon();
  get_css();
  get_js("product");
  ?>
</head>


<body>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/header.php"; ?>

  <main>

    <section id="product" class="block">
      <div class="container">

        <div class="product_image">
          <img src="/assets/img/products/<?php echo $product["image"]; ?>">
        </div>

        <div class="product_details">
          <h1><?php echo $product["name"]; ?></h1>
          <h2 class="subheading"><?php echo $product["manufacturer"]; ?></h2>
          <p class="flex clr_tertiary">
            <span class="fs-l">&dollar;<?php echo $product["price"]; ?></span> <span class="flex_align_self_center fs-xs">- Best price -</span>
          </p>
          <p>
            <?php echo nl2br($product["description"]); ?>
          </p>
          <?php if (is_logged_in()) { ?>
            <form class="form button" action="/product.php?guid=<?php echo $product["guid"]; ?>&mode=purchase" method="post">
              <?php if ($error && $error_location == "purchase") { ?>
                <div class="input_group">
                  <label class="error"><?php echo $error_msg; ?></label>
                </div>
              <?php } ?>
              <div class="input_group">
                <input type="text" name="user_guid" id="user_guid_purchase" placeholder="" value="<?php echo $_SESSION['user_guid']; ?>">
                <input type="text" name="product_guid" id="product_guid" placeholder="" value="<?php echo $product_guid; ?>">
              </div>
              <input type="submit" value="Purchase now!" class="short">
              <div class="input_group">
                <textarea name="purchase_note" id="purchase_note" placeholder="Your purchase note"></textarea>
              </div>
            </form>
          <?php } ?>
        </div>

      </div>
    </section>

    <?php if (is_logged_in()) { ?>

      <section id="comment" class="block">
        <div class="container">

          <form class="form" action="/product.php?guid=<?php echo $product["guid"]; ?>&mode=submit" method="post">
            <div class="input_group">
              <h2>Write a comment</h2>
            </div>
            <?php if ($error && $error_location == "comment") { ?>
              <div class="input_group">
                <label class="error"><?php echo $error_msg; ?></label>
              </div>
            <?php } ?>
            <div class="input_group">
              <label for="title">Title</label>
              <input type="text" name="title" id="title" placeholder="" required>
            </div>
            <div class="input_group">
              <label for="comment">Comment</label>
              <textarea name="comment" id="comment" placeholder="" required></textarea>
            </div>
            <div class="input_group">
              <input type="text" name="user_guid" id="user_guid_comment" placeholder="" value="<?php echo $_SESSION['user_guid']; ?>">
              <input type="text" name="product_guid" id="product_guid" placeholder="" value="<?php echo $product_guid; ?>">
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
            <h2>Comments</h2>
          </div>

          <?php foreach (get_product_comments($product_guid) as $comment): ?>
            <?php $username = get_username($comment["user_guid"]); ?>
            <div class="user_comment" guid="<?php echo $comment["user_guid"]; ?>">
              <p class="username"><?php echo $username; ?></p>
              <p class="comment_title"><?php echo $comment['title']; ?></p>
              <p class="comment"><?php echo nl2br($comment['comment']); ?></p>
            </div>
          <?php endforeach; ?>

        </div>
      </div>
    </section>

  </main>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/footer.php"; ?>

</body>

</html>