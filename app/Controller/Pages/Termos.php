<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Termos extends Page
{

  public static function getTermos()
  {
    $content = View::render('pages/termos', []);

    /* Retorna a View da Página */
    return parent::getPage('TERMOS > GMFARM', $content);
  }
}
