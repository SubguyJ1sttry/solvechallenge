<?php

function get_meta()
{
   echo "<meta charset=\"utf-8\"/>";
   echo "<meta name=\"author\" content=\"Lukas Reinert\" />";
   echo "<meta name=\"description\" content=\"\" />";
   echo "<meta name=\"keywords\" content=\"\" />";
   echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" />";
}

function get_favicon()
{
   echo "<link rel=\"shortcut icon\" href=\"/assets/img/favicon.png\">";
}

function get_css()
{
   $folder = "/assets/css/";
   $path = $_SERVER["DOCUMENT_ROOT"] . $folder;

   $whitelist = glob($path . "*.css");
   $whitelist[] = $_SERVER["DOCUMENT_ROOT"] . "/assets/fonts/fontawesome-6.5.2/css/all.min.css";
   $blacklist = [$folder . "variables.css"];
   foreach ($whitelist as $css) {
      $css = str_replace($_SERVER["DOCUMENT_ROOT"], '', $css);

      if (in_array($css, $blacklist)) {
         continue;
      }
      echo "<link type=\"text/css\" rel=\"stylesheet\" href=\"$css\">";
   }
}

function get_js($location)
{
   $folder = "/assets/js/";
   $path = $_SERVER["DOCUMENT_ROOT"] . $folder;

   $defer = [$folder . "main.js", $folder . "aos.js", $folder . "showData.js", $folder . "typewriter.js"];
   $whitelist = glob($path . "*.js");
   $blacklist = [""];
   foreach ($whitelist as $js) {
      $js = str_replace($_SERVER["DOCUMENT_ROOT"], '', $js);

      if (in_array($js, $blacklist)) {
         continue;
      }

      if (in_array($js, $defer)) {
         echo "<script type=\"text/javascript\" src=\"$js\" defer></script>";
      } else {
         echo "<script type=\"text/javascript\" src=\"$js\"></script>";
      }
   }
}
