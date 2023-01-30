<?php

require __DIR__ . '/includes/app.php';

use \App\Http\Router;

/* Inicia o Router */

$obRouter = new Router(URL);

/* Inclui as Rotas */
include __DIR__ . '/routes/pages.php';

/* Inclui as Rotas */
include __DIR__ . '/routes/admin.php';

/* Imprime o response da rota */
$obRouter->run()
  ->sendResponse();
