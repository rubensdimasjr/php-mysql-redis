<?php

use \App\Http\Response;
use \App\Controller\Admin;

/* Rota de Listagem de Materiais */

$obRouter->get('/admin/materiais', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Material::getMaterial($request));
  }
]);

/* Rota de cadastro de um novo materia POST */

$obRouter->post('/admin/materiais', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Material::setNewMaterial($request));
  }
]);

/* Rota de Edição de Materiais */

$obRouter->get('/admin/materiais/{id_material}/edit', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id_material) {
    return new Response(200, Admin\Material::getEditMaterial($request, $id_material));
  }
]);

/* Rota de Edição de Materiais (post) */

$obRouter->post('/admin/materiais/{id_material}/edit', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id_material) {
    return new Response(200, Admin\Material::setEditMaterial($request, $id_material));
  }
]);

/* Rota de exclusão de Materiais */

$obRouter->get('/admin/materiais/{id_material}/delete', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id_material) {
    return new Response(200, Admin\Material::getDeleteMaterial($request, $id_material));
  }
]);

/* Rota de exclusão de Materiais (POST) */

$obRouter->post('/admin/materiais/{id_material}/delete', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id_material) {
    return new Response(200, Admin\Material::setDeleteMaterial($request, $id_material));
  }
]);
