<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

/* O Controlador vai ser responsável por receber uma ação, executar o Model para obter os dados necessários e depois passar os dados para a View */

/**
 * Método responsável por retornar o conteudo da nossa home.
 * @return string
 */
class Home extends Page
{

  public static function getHome()
  {

    $obOrganization = new Organization;

    /* View da Home */
    $content = View::render('pages/home', [
      'welcome' => $obOrganization->welcome,
      'description' => $obOrganization->description
    ]);


    /* Retorna a View da Página */
    return parent::getPage('HOME > GMFARM', $content);
  }
}
