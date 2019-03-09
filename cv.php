<?php


include_once("php/layout.class.php");
include_once("php/autoload.include.php");


$p = new BootstrapPage("CV");
$nav = Layout::nav(2);
$p->setLanguage("fr");
$p->appendContent($nav);

$p->appendContent(<<<HTML
<div class ="container container-mod">
  <h1  style='text-align : center'> CV </h1>
    <div class="drop">
      <h2>Formations</h2>
      <hr class="hrPart"/>
      <ul>
        <li> 2004 : Doctorat Instrumentation et Informatique de l'Image</li>
        <li> 2001 : DEA Instrumentation et Informatique de l'Image</li>
        <li> 1997 : Maîtrise de Mathématiques pures</li>
        <li> 2000 : Brevet d'État d'Éducateur Sportif dans les Activités de la Natation (BEESAN) premier degré</li>
      </ul>
    </div>

    <div class="drop">
      <h2>Exprérience professionnelle</h2>
      <hr class="hrPart"/>
      <ul>
        <li> 2006 - ... : MCF à l'UFR Sciences et Techniques, au département IEM de Dijon</li>
        <li> 2005 - 2006 : ATER à l'UFR STAPS de Dijon</li>
        <li> 2004 - 2005 : ATER au département Informatique de l'IUT de Reims</li>
        <li> 2001 - 2004 : Allocataire-Moniteur au département IEM et en IUT SRC</li>
        <li> 2000 - 2001 : Vacataire en Mathématiques à l'Université de Technologie de Belfort - Montbéliard</li>
        <li> 1998 - 2001 : Formatrice en Mathématiques en BTS MI</li>
      </ul>
    </div>

    <div class="drop">
      <h2>Loisirs</h2>
      <hr class="hrPart"/>
      <ul>
        <li> Badminton, Roller, Natation, Funboard, Beach-Volley, Catamaran</li>
        <li> Saxophone, Guitare</li>
      </ul>
    </div>
  </div>
  <hr>
  <hr>
HTML
);

$p->appendContent(Layout::footer());
echo $p->toHTML();
