<?php

namespace App\Session\Admin;

class Login
{

  /**
   * Método responsável por iniciar a sessão
   */
  private static function init()
  {

    /* VERIFICA SE SESSÃO NÃO ESTÁ ATIVA */
    if (session_status() != PHP_SESSION_ACTIVE) {
      session_start();
    }
  }

  /**
   * Método responsável por criar o login do usuário
   * @param User $obUser
   * @return boolean
   */
  public static function login($obUser)
  {

    /* INICIA A SESSÃO */
    self::init();

    /* DEFINE A SESSÃO DO USUÁRIO */
    $_SESSION['admin']['usuario'] = [
      'id' => $obUser->id,
      'nome' => $obUser->nome,
      'email' => $obUser->email,
      'tipo_usuario' => $obUser->tipo_usuario
    ];

    /* RETORNA SUCESSO */
    return true;
  }

  /**
   * Método responsável por verificar se o usuário está logado
   * @return boolean
   */
  public static function isLoged()
  {

    /* INICIA A SESSÃO */
    self::init();

    /* RETORNA A VERIFICAÇÃO */
    return isset($_SESSION['admin']['usuario']['id']);
  }

  public static function typeLogin()
  {

    /* INICIA A SESSÃO */
    self::init();

    /* RETORNA A VERIFICAÇÃO */
    return $_SESSION['admin']['usuario']['tipo_usuario'];
  }

  /**
   * Método responsável por executar o logou do usuário
   * @return boolean
   */
  public static function logout()
  {

    /* INICIA A SESSÃO */
    self::init();

    /* DESLOGA O USUÁRIO */
    unset($_SESSION['admin']['usuario']);

    /* SUCESSO */
    return true;
  }
}
