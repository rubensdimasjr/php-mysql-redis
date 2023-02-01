<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;
use \Rubensdimas\RedisManager\Redis;
use stdClass;

class Alunos extends Page
{

  /**
   * Método responsável por obter a renderização dos atributos do aluno 
   * @param Request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getAlunoAtributos($request, &$obPagination)
  {
    /* ALUNOS */
    $atributos = '';

    /* Quantidade total de registros */
    $quantidadeTotal = EntityUser::getAtributos(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

    /* Página atual */
    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;

    /* Instancia de paginação */
    $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 20);

    /**
     * EXIBIÇÃO DOS DADOS
     */
    foreach(EntityUser::getAllDataOrCache($obPagination) as $item){
      $atributos .= View::render('admin/modules/alunos/atributo', [
        'id' => $item['id'],
        'nome' => $item['nome'],
        'email' => $item['email'],
        'matricula' => $item['matricula']
      ]);
    }

    /* RETORNA OS ALUNOS */
    return $atributos;
  }

  /**
   * Método responsável por renderizar a view de Alunos
   * @return string
   */
  public static function getAluno($request)
  {
    /* CONTEÚDO DA HOME */
    $content = View::render('admin/modules/alunos/index', [
      'atributos' => self::getAlunoAtributos($request, $obPagination),
      'pagination' => parent::getPagination($request, $obPagination),
      'status' => self::getStatus($request)
    ]);

    /* RETORNA A PÁGINA COMPLETA */
    return parent::getPanel('Alunos > GMFARM', $content, 'alunos');
  }

  /**
   * Método responsável por cadastrar um aluno no banco
   * @param Request
   * @return string
   */
  public static function setNewAluno($request)
  {
    /* DADOS DO POST */
    $postVars = $request->getPostVars();

    $senhaCrip = password_hash($postVars['senha'], PASSWORD_BCRYPT);

    /* NOVA INSTANCIA DE ESTOQUE */
    $obUser = new EntityUser;
    $obUser->nome = $postVars['nome'];
    $obUser->email = $postVars['email'];
    $obUser->matricula = $postVars['matricula'];
    $obUser->senha = $senhaCrip;
    $obUser->cadastrar();

    /* RETORNA PARA PÁGINA DE EDIÇÃO */
    $request->getRouter()->redirect('/admin/alunos/' . $obUser->id . '/edit?status=created');
  }


  /**
   * Método responsável por retornar a mensagem de status
   * @param Request $request
   * @return string
   */
  private static function getStatus($request)
  {

    /* QUERY PARAMS */
    $queryParams = $request->getQueryParams();

    /* STATUS */
    if (!isset($queryParams['status'])) return '';

    /* MENSAGENS DE STATUS */
    switch ($queryParams['status']) {
      case 'created':
        return Alert::getSuccess('Aluno cadastrado com sucesso!');
        break;
      case 'updated':
        return Alert::getSuccess('Dados do aluno atualizados com sucesso!');
        break;
      case 'deleted':
        return Alert::getSuccess('Aluno deletado com sucesso!');
        break;
    }
  }

  /**
   * Método responsável por retornar o formulário de editação de um aluno
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getEditAluno($request, $id)
  {

    /* OBTEM ALUNO NO BANCO DE DADOS ou REDIS */
    $obUser = (object) EntityUser::getDataOrCache($id);

    /* CASO NÃO EXISTA */
    if (!$obUser instanceof EntityUser && !$obUser instanceof stdClass) {
      $request->getRouter()->redirect('/admin/alunos');
    }

    /* CONTEUDO DO FORMULÁRIO */
    $content = View::render('admin/modules/alunos/form', [
      'title' => 'Editar Aluno',
      'nome' => $obUser->nome,
      'email' => $obUser->email,
      'matricula' => $obUser->matricula,
      'status' => self::getStatus($request)
    ]);

    /* RETORNA A PÁGINA */
    return parent::getPanel('Editar aluno > GMFARM', $content, 'alunos');
  }

  /**
   * Método responsável por gravar a atualização de um aluno
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setEditAluno($request, $id)
  {

    /* OBTEM ALUNO NO BANCO DE DADOS ou REDIS */
    $obUser = EntityUser::getAlunoById($id);

    /* CASO NÃO EXISTA */
    if (!$obUser instanceof EntityUser) {
      $request->getRouter()->redirect('/admin/alunos');
    }

    /* POST VARS */
    $postVars = $request->getPostVars();

    $cripSenha = null;

    if ($postVars['senha'] !== '') {
      $cripSenha = password_hash($postVars['senha'], PASSWORD_BCRYPT);
    }

    /* ATUALIZA A INSTANCIA */
    $obUser->nome = $postVars['nome'] ?? $obUser->nome;
    $obUser->email = $postVars['email'] ?? $obUser->email;
    $obUser->matricula = $postVars['matricula'] ?? $obUser->matricula;
    $obUser->senha = $cripSenha ?? '';
    $obUser->atualizar();

    /**
     * ATUALIZA OS DADOS NO REDIS
     */
    $obRedis = new Redis;
    $obRedis->edit('alunos', 'aluno'.$id, [
      'id' => $id,
      'nome' => $postVars['nome'] ?? $obUser->nome,
      'email' => $postVars['email'] ?? $obUser->email,
      'matricula' => $postVars['matricula'] ?? $obUser->matricula
    ]);

    /* REDIRECIONA O USUARIO */
    $request->getRouter()->redirect('/admin/alunos/' . $obUser->id . '/edit?status=updated');
  }

  /**
   * Método responsável por retornar o formulário de exclusão de um aluno
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getDeleteAluno($request, $id)
  {

    /* OBTEM ALUNO NO BANCO DE DADOS ou REDIS */
    $obUser = (object) EntityUser::getDataOrCache($id);

    /* CASO NÃO EXISTA */
    if (!$obUser instanceof EntityUser && !$obUser instanceof stdClass) {
      $request->getRouter()->redirect('/admin/alunos');
    }

    /* CONTEUDO DO FORMULÁRIO */
    $content = View::render('admin/modules/alunos/delete', [
      'id' => $obUser->id,
      'title' => 'Deletar Aluno',
      'nome' => $obUser->nome
    ]);

    /* RETORNA A PÁGINA */
    return parent::getPanel('Deletar aluno > GMFARM', $content, 'alunos');

  }

  /**
   * Método responsável por fazer a exclusão de um aluno
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setDeleteAluno($request, $id)
  {

    /* OBTEM ALUNO NO BANCO DE DADOS ou REDIS */
    $obUser = EntityUser::getAlunoById($id);

    /* CASO NÃO EXISTA */
    if (!$obUser instanceof EntityUser) {
      $request->getRouter()->redirect('/admin/alunos');
    }

    /* EXCLUI O MATERIAL */
    $obUser->excluir();

    /* EXLUI DO CACHE */
    $obRedis = new Redis();
    $obRedis->delete('alunos', 'aluno'.$id);

    /* REDIRECIONA O USUARIO */
    $request->getRouter()->redirect('/admin/alunos?status=deleted');

  }
}
