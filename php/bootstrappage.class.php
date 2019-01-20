<?php

require_once "autoload.include.php";

/**
 * This class provides methods in order to ease
 * the production of a HTML5 Web page using the
 * Bootstrap framework.
 *
 * @author Olivier Nocent (olivier.nocent@univ-reims.fr)
 * @version 1.0
 */
class BootstrapPage extends WebPage {
  /**
   * Generate the HTML5 code of the Web page as a string
   *
   * @return string HTML5 code
   */
  public function toHTML() {
    return <<<HTML
<!doctype html>
<html lang="{$this->lang}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{$this->title}</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery-ui.min.css">
    <link rel="stylesheet" href="css/jquery.themes.css">
    <link href="https://fonts.googleapis.com/css?family=Amiko" rel="stylesheet">

     <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    {$this->head}
    <link rel="stylesheet" href="css/style.css">

  </head>
  <body>
      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/jquery.themes.min.js"></script>
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.3/jquery.min.js"></script>-->

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
  
      {$this->body}
      {$this->js}
    </div>
  </body>
</html>
HTML;
  }
}
