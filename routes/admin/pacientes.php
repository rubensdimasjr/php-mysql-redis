<?php

use \App\Http\Response;
use \App\Controller\Admin;

/* Rota de CRUD de Pacientes */

$obRouter->get('/admin/pacientes', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Pacientes::getPaciente($request));
  }
]);

/* Rota de Criação de Pacientes */

$obRouter->post('/admin/pacientes', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Pacientes::setNewPaciente($request));
  }
]);

/* Rota de Edição de Pacientes */

$obRouter->get('/admin/pacientes/{id}/edit', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Pacientes::getEditPaciente($request, $id));
  }
]);

/* Rota de Edição de Pacientes (post) */

$obRouter->post('/admin/pacientes/{id}/edit', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Pacientes::setEditPaciente($request, $id));
  }
]);

/* Rota de exclusão de Pacientes */

$obRouter->get('/admin/pacientes/{id}/delete', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Pacientes::getDeletePaciente($request, $id));
  }
]);

/* Rota de exclusão de Pacientes (POST) */

$obRouter->post('/admin/pacientes/{id}/delete', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Pacientes::setDeletePaciente($request, $id));
  }
]);
