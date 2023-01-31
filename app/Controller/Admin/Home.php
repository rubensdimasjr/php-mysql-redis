<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Home extends Page
{

  /**
   * Método responsável por renderizar a view de Home
   * @return string
   */
  public static function getHome($request)
  {
    /* CONTEÚDO DA HOME */
    $content = View::render('admin/modules/home/index', [
    ]);


    /* RETORNA A PÁGINA COMPLETA */
    return parent::getPanel('Home > GMFARM', $content, 'home');
  }
}
