<?php

use \App\Http\Response;
use \App\Controller\Admin;

/* Rota de CRUD de Alunos */

$obRouter->get('/admin/alunos', [
  'middlewares' => [
    'required-admin-login',
    'required-admin-type'
  ],
  function ($request) {
    return new Response(200, Admin\Alunos::getAluno($request));
  }
]);

/* Rota de cadastro de um novo aluno via POST */

$obRouter->post('/admin/alunos', [
  'middlewares' => [
    'required-admin-login',
    'required-admin-type'
  ],
  function ($request) {
    return new Response(200, Admin\Alunos::setNewAluno($request));
  }
]);

/* Rota de Edição de Alunos */

$obRouter->get('/admin/alunos/{id}/edit', [
  'middlewares' => [
    'required-admin-login',
    'required-admin-type'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Alunos::getEditAluno($request, $id));
  }
]);

/* Rota de Edição de Alunos (post) */

$obRouter->post('/admin/alunos/{id}/edit', [
  'middlewares' => [
    'required-admin-login',
    'required-admin-type'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Alunos::setEditAluno($request, $id));
  }
]);

/* Rota de exclusão de Alunos */

$obRouter->get('/admin/alunos/{id}/delete', [
  'middlewares' => [
    'required-admin-login',
    'required-admin-type'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Alunos::getDeleteAluno($request, $id));
  }
]);

/* Rota de exclusão de Alunos (POST) */

$obRouter->post('/admin/alunos/{id}/delete', [
  'middlewares' => [
    'required-admin-login',
    'required-admin-type'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Alunos::setDeleteAluno($request, $id));
  }
]);
