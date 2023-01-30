<?php

use \App\Http\Response;
use \App\Controller\Admin;

/* Rota ADMIN Perfil Usuário*/

$obRouter->get('/admin/perfil', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Perfil::getPerfil($request));
  }
]);

$obRouter->post('/admin/perfil', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Perfil::setSenha($request));
  }
]);
