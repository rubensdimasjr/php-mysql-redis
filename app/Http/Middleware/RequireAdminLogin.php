<?php

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogin
{

  /**
   * Método responsável por executar o middleware
   * @param Request $request
   * @param Closure $next
   * @return Response
   */
  public function handle($request, $next)
  {

    /* VERIFICA SE O USUARIO ESTÁ LOGADO */
    if (!SessionAdminLogin::isLoged()) {
      $request->getRouter()->redirect('/admin/login');
    }

    /* CONTINUA A EXECUÇÃO */
    return $next($request);
  }
}
