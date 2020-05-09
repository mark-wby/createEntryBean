<?php

require __DIR__."/../vendor/autoload.php";

$name = \MarkWang\AutoCreateEntryBean\CreateEntryBean::parseName("book_name",1);
var_dump($name);
