<?php

require_once "php/autoload.include.php";

Admin::logoutIfRequested();

header("Location: index.php");