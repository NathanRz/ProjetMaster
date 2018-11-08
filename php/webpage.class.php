<?php

require_once "autoload.include.php";

/**
 * This class provides methods in order to ease 
 * the production of a HTML5 Web page
 *
 * @author Jérôme Cutrona (jerome.cutrona@univ-reims.fr)
 * @author Olivier Nocent (olivier.nocent@univ-reims.fr)
 * @version 1.0
 */
class WebPage {
  /**
   * @var string Language in which the content is written
   */
  protected $lang = null;

  /**
   * @var string Text nested in the <title> element (mandatory)
   */
  protected $title = null;

  /**
   * @var string Content nested in the <head> element (mainly CSS code)
   */
  protected $head  = null;
    
  /**
   * @var string Content nested in the <body> element
   */
  protected $body  = null;

  /**
   * @var string Scripts inserted after the content nested in the <body> element,
   *             for performance purposes
   */
  protected $js  = null;

  /**
   * Constructor
   * @param string $title Page title (mandatory)
   *
   * @return void
   */
  public function __construct($title) {
    $this->setLanguage("fr");
    $this->setTitle($title);
  }

  /**
   * Escape special characters not allowed in the content of a Web page (for example <, >, &).
   * @see http://php.net/manual/en/function.htmlentities.php
   * @param string $content Content to be escaped
   *
   * @return string Escaped content
   */
  public static function escapeString($content) {
    return htmlentities($content, ENT_QUOTES|ENT_HTML5, "utf-8");
  }

  /**
   * Set the language in which the content is written
   * @param string $lang Language codename (en, fr, pt, ...)
   *
   * @return void
   */
  public function setLanguage($lang) {
    $this->lang = $lang;
  }

  /**
   * Set the title of the Web page
   * @param string $title Title
   *
   * @return void
   */
  public function setTitle($title) {
    $this->title = $title;
  }

  /**
   * Add content nested in the <head> element
   * @param string $content Content to be added
   *
   * @return void
   */
  public function appendToHead($content) {
    $this->head .= $content;
  }

  /**
   * Add CSS code nested in the <head> element
   * @param string $css CSS code to be added
   *
   * @return void
   */
  public function appendCss($css) {
    $this->appendToHead(<<<HTML
    <style>
      {$css}
    </style>

HTML
);
  }

  /**
   * Add the URL of an external CSS file
   * @param string $url CSS file URL
   *
   * @return void
   */
  public function appendCssUrl($url) {
    $this->appendToHead(<<<HTML
    <link rel="stylesheet" href="{$url}">

HTML
);
  }

  /**
   * Add JavaScript code
   * @param string $js JavaScript code to be added
   *
   * @return void
   */
  public function appendJs($js) {
    $this->js .= <<<HTML
    <script type='text/javascript'>
    {$js}
    </script>

HTML;
  }

  /**
   * Add the URL of an external JavaScript file
   * @param string $url JavaScript file URL
   *
   * @return void
   */
  public function appendJsUrl($url) {
    $this->js .= <<<HTML
    <script type='text/javascript' src='{$url}'></script>

HTML;
  }

  /**
   * Add content nested in the <body> element
   * @param string $content Content to be added
   *
   * @return void
   */
  public function appendContent($content) {
    $this->body .= $content ;
  }

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
          <link rel="stylesheet" href="css/style.css">
          <title>{$this->title}</title>
          {$this->head}
        </head>
        <body>
          {$this->body}
          {$this->js}
        </body>
      </html>
HTML;
  }
}

/**
 * PHP sample file

<?php

require_once("webpage.class.php");

$page = new WebPage("My first steps in HTML5");

$page->setLanguage("en");

$page->appendCss(<<<CSS
body {
  font-family: sans-serif;
  color: blue;
}

CSS
);

$page->appendContent(<<<HTML
<h1>Hello World Wide Web!</h1>

<h2>Caution:</h2>
<p>

HTML
);

$page->appendContent(WebPage::escapeString("The characters <, > and & are not allowed within the content of an HTML document."));

echo $page->toHTML();

*/