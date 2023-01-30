<?php

use \App\Http\Response;
use \App\Controller\Admin;

/* Rota de CRUD de Alunos */

$obRouter->get('/admin/calculadora', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Calculadora::getCalculadora($request));
  }
]);

$obRouter->post('/admin/calculadora', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Calculadora::setCalculo($request));
  }
]);
