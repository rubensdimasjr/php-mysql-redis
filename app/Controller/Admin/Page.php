<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Session\Admin\Login as LoginAdmin;

class Page
{

  /**
   * Módulos disponíveis no painel
   * @var array
   */
  private static $modules = [
    'home' => [
      'label' => 'Home',
      'link' => URL . '/admin'
    ],
    'materiais' => [
      'label' => 'Materiais',
      'link' => URL . '/admin/materiais'
    ],
    'alunos' => [
      'label' => 'Alunos',
      'link' => URL . '/admin/alunos'
    ],
    'pacientes' => [
      'label' => 'Pacientes',
      'link' => URL . '/admin/pacientes'
    ],
    'receitas' => [
      'label' => 'Receitas',
      'link' => URL . '/admin/receitas'
    ],
    'calculadora' => [
      'label' => 'Calculadora',
      'link' => URL . '/admin/calculadora'
    ],
    'perfil' => [
      'label' => 'Perfil',
      'link' => URL . '/admin/perfil'
    ]
  ];

  /**
   * Método responsável por retornar o conteúdo (view) da estrutura genérica de página do painel
   * @param string $title 
   * @param string $content
   * @param string $urlLink
   * @return string
   */
  public static function getPage($title, $content, $style)
  {
    return View::render('admin/page', [
      'title' => $title,
      'content' => $content,
      'style' => $style
    ]);
  }

  /**
   * Método responsável por renderizar a view do menu do painel
   * @param string $currentModule
   * @return string
   */
  private static function getMenu($currentModule)
  {

    /* LNKS DO MENU */
    $links = '';

    $obTypeUser = LoginAdmin::typeLogin();

    if ($obTypeUser !== 'admin') {
      unset(self::$modules['alunos']);
    }

    /* ITERA OS MODULOS */
    foreach (self::$modules as $hash => $module) {
      $links .= View::render('admin/menu/link', [
        'label' => $module['label'],
        'link'  => $module['link'],
        'current' => $hash == $currentModule ? 'active' : ''
      ]);
    }

    /* RENDERIZA A VIEW DO MENU */
    return View::render('admin/menu/box', [
      'links' => $links
    ]);
  }

  /**
   * Método responsável por renderizar a view do menu do painel
   * @param string $currentModule
   * @return string
   */
  private static function getFooter()
  {

    /* RENDERIZA A VIEW DO FOOTER */
    return View::render('admin/footer/footer', []);
  }

  /**
   * Método responsável por renderizar a view do painel
   * @param string $title
   * @param string $content
   * @param string $currentModule
   * @return string
   */
  public static function getPanel($title, $content, $currentModule)
  {

    /* RENDERIZA A VIEW DO PAINEL */
    $contentPanel = View::render('admin/panel', [
      'menu' => self::getMenu($currentModule),
      'content' => $content,
      'footer' => self::getFooter()
    ]);

    /* RETORNA A PÁGINA RENDERIZADA */
    return self::getPage($title, $contentPanel, '');
  }

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
      $links .= View::render('admin/pagination/link', [
        'page' => $page['page'],
        'link' => $link,
        'active' => $page['current'] ? 'active' : ''
      ]);
    }

    /* Renderiza box de paginação */
    return View::render('admin/pagination/box', [
      'links' => $links
    ]);
  }
}
