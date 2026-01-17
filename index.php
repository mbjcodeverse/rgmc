<?php	
require_once "controllers/template.controller.php";

require_once "extensions/vendor/autoload.php";

require_once "controllers/employees.controller.php";
require_once "models/employees.model.php";

require_once "controllers/category.controller.php";
require_once "models/category.model.php";

require_once "controllers/supplier.controller.php";
require_once "models/supplier.model.php";

require_once "controllers/product.controller.php";
require_once "models/product.model.php";

require_once "controllers/brand.controller.php";
require_once "models/brand.model.php";

require_once "controllers/measure.controller.php";
require_once "models/measure.model.php";

// require_once "controllers/users.controller.php";
// require_once "models/users.model.php";

// ========================================================

require_once "controllers/purchaseorder.controller.php";
require_once "models/purchaseorder.model.php";

require_once "controllers/incoming.controller.php";
require_once "models/incoming.model.php";

require_once "controllers/machine.controller.php";
require_once "models/machine.model.php";

require_once "controllers/classification.controller.php";
require_once "models/classification.model.php";

require_once "controllers/building.controller.php";
require_once "models/building.model.php";

require_once "controllers/items.controller.php";
require_once "models/items.model.php";

require_once "controllers/inventory.controller.php";
require_once "models/inventory.model.php";

require_once "controllers/inventoryrawmats.controller.php";
require_once "models/inventoryrawmats.model.php";

require_once "controllers/stockout.controller.php";
require_once "models/stockout.model.php";

require_once "controllers/return.controller.php";
require_once "models/return.model.php";

require_once "controllers/rawmats.controller.php";
require_once "models/rawmats.model.php";

require_once "controllers/goods.controller.php";
require_once "models/goods.model.php";

require_once "controllers/incomingrawmats.controller.php";
require_once "models/incomingrawmats.model.php";

require_once "controllers/rawout.controller.php";
require_once "models/rawout.model.php";

require_once "controllers/prodcom.controller.php";
require_once "models/prodcom.model.php";

require_once "controllers/prodfin.controller.php";
require_once "models/prodfin.model.php";

require_once "controllers/recycle.controller.php";
require_once "models/recycle.model.php";

require_once "controllers/debris.controller.php";
require_once "models/debris.model.php";

require_once "controllers/factorydashboard.controller.php";
require_once "models/factorydashboard.model.php";

require_once "controllers/userrights.controller.php";
require_once "models/userrights.model.php";

require_once "controllers/excess.controller.php";
require_once "models/excess.model.php";

require_once "controllers/matreturn.controller.php";
require_once "models/matreturn.model.php";

require_once "controllers/othercost.controller.php";
require_once "models/othercost.model.php";

require_once "controllers/prodmetrics.controller.php";
require_once "models/prodmetrics.model.php";

require_once "controllers/machinetracking.controller.php";
require_once "models/machinetracking.model.php";

// require_once "extensions/vendor/mike42/escpos-php/src/Mike42/Escpos/printer.php";
// require_once "extensions/vendor/mike42/escpos-php/src/Mike42/Escpos/EscposImage.php";
// require_once "extensions/vendor/mike42/escpos-php/src/Mike42/Escpos/PrintConnectors/FilePrintConnector.php";
// require_once "extensions/vendor/mike42/escpos-php/src/Mike42/Escpos/PrintConnectors/WindowsPrintConnector.php";

require_once "controllers/home.controller.php";
require_once "models/home.model.php";

$template = new ControllerTemplate();
$template -> ctrTemplate();