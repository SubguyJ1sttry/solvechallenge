<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/html_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/helper_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/mysql_functions.php";
start_session();
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <title>Welcome | Routerploit</title>
  <?php
  get_meta();
  get_favicon();
  get_css();
  get_js("index");
  ?>
</head>


<body>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/header.php"; ?>

  <main>

    <section id="welcome" class="block">
      <div class="container">

        <h1>Welcome</h1>
        <p>
          We have the network devices fitting all your needs from private to enterprise usage.
        </p>

      </div>
    </section>

    <section id="products" class="block">
      <div class="container">

        <?php foreach (get_products() as $product): ?>
          <div class="item">
            <a href="/product.php?guid=<?php echo $product["guid"]; ?>" class="itemlink"><span></span></a>
            <img src="/assets/img/products/<?php echo $product["image"]; ?>">
            <h2><?php echo $product["name"]; ?></h2>
            <p><?php echo $product["short_description"]; ?></p>
          </div>
        <?php endforeach; ?>

      </div>
    </section>

  </main>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/../outsource/footer.php"; ?>

</body>

</html>