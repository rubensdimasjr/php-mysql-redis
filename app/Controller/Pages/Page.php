<?php

namespace App\Controller\Pages;

use \App\Utils\View;

/* O Controlador vai ser responsável por receber uma ação, executar o Model para obter os dados necessários e depois passar os dados para a View */

/**
 * Método responsável por retornar o conteudo da nossa página genérica.
 * @return string
 */
class Page
{
  /**
   * Método responsável por renderizar o topo da página
   * @return string
   */
  private static function getHeader()
  {
    return View::render('pages/header');
  }

  /**
   * Método responsável por renderizar o rodapé da página
   * @return string
   */
  private static function getFooter()
  {
    return View::render('pages/footer');
  }

  /**
   * Método responsável por renderizar o layout de paginação
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  public static function getPagination($request, $obPagination)
  {

    /* páginas */
    $pages = $obPagination->getPages();

    /* Verifica a quantidade de páginas */
    if (count($pages) <= 1) return '';

    /* links */
    $links = '';

    /* URL Atual sem gets */
    $url = $request->getRouter()->getCurrentUrl();

    /* get */
    $queryParams = $request->getQueryParams();

    /* Renderiza os links */
    foreach ($pages as $page) {

      /* Altera a página */
      $queryParams['page'] = $page['page'];

      /* Link da página */
      $link = $url . '?' . http_build_query($queryParams);

      /* View */
      $links .= View::render('pages/pagination/link', [
        'page' => $page['page'],
        'link' => $link,
        'active' => $page['current'] ? 'active' : ''
      ]);
    }

    /* Renderiza box de paginação */
    return View::render('pages/pagination/box', [
      'links' => $links
    ]);
  }

  public static function getPage($title, $content)
  {
    return View::render('pages/page', [
      'title' => $title,
      'header' => self::getHeader(),
      'content' => $content,
      'footer' => self::getFooter()
    ]);
  }
}
