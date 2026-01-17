<?php
echo "<script>alert('mom');</script>";
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

// echo '<script>alert("gehl");</script>';
$printer = "epsonreceipt";
$connector = new WindowsPrintConnector($printer);
$setprinter = new Printer($connector);
$setprinter->text("Hello Mom"."\n");
$setprinter->cut();
$setprinter->close();