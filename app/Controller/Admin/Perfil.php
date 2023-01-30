<?php

namespace App\Controller\Admin;

use App\Utils\View;
use \App\Model\Entity\User as EntityUser;

class Perfil extends Page
{

  public static function getPerfil($request, $messageError = null, $messageSuccess = null)
  {

    /* STATUS */
    $statusError = !is_null($messageError) ? Alert::getError($messageError) : '';
    $statusSuccess = !is_null($messageSuccess) ? Alert::getSuccess($messageSuccess) : '';

    $id = $_SESSION['admin']['usuario']['id'];
    $obUser = EntityUser::getAlunoById($id);

    $content = View::render('admin/modules/perfil/index', [
      'nome' => $obUser->nome,
      'email' => $obUser->email,
      'matricula' => $obUser->matricula,
      'senha' => $obUser->senha,
      'statusError' => $statusError,
      'statusSuccess' => $statusSuccess
    ]);

    /* RETORNA A PÁGINA COMPLETA */
    return parent::getPanel('Perfil > GMFARM', $content, 'perfil');
  }


  /**
   * Método responsável por redefinir a senha
   * @return string
   */
  public static function setSenha($request)
  {
    $postVars = $request->getPostVars();

    $id = $_SESSION['admin']['usuario']['id'];
    $obUser = EntityUser::getAlunoById($id);

    /* VERIFICA A SENHA DO USUÁRIO */
    if (!password_verify($postVars['senha'], $obUser->senha)) {
      return self::getPerfil($request, 'A senha digitada não coincide com a senha atual!', null);
      exit;
    }

    $cripSenha = password_hash($postVars['nsenha'], PASSWORD_BCRYPT);

    /* ATUALIZA A SENHA */
    $obUser->senha = $cripSenha;
    $obUser->atualizar();

    return self::getPerfil($request, null, 'Senha alterada com sucesso!');
  }
}
