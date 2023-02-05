<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Paciente as EntityPaciente;
use \Rubensdimas\DatabaseManager\Pagination;

class Pacientes extends Page
{

  private static $generoModules = [
    'M' => [
      'id' => 'genero1',
      'label' => 'Masculino',
      'value' => 'M'
    ],
    'F' => [
      'id' => 'genero2',
      'label' => 'Feminino',
      'value' => 'F'
    ]
  ];

  private static $situacaoModules = [
    'Atendido' => [
      'id' => 'situacao1',
      'value' => 'Atendido'
    ],
    'Agendado' => [
      'id' => 'situacao2',
      'value' => 'Agendado'
    ],
    'Indeferido' => [
      'id' => 'situacao3',
      'value' => 'Indeferido'
    ]
  ];

  /**
   * Método responsável por cadastrar um paciente no banco
   * @param Request
   * @return string
   */
  public static function setNewPaciente($request)
  {

    /* DADOS DO POST */
    $postVars = $request->getPostVars();

    /* NOVA INSTANCIA DE PACIENTE */
    $obPaciente = new EntityPaciente;
    $obPaciente->nome = $postVars['nome'];
    $obPaciente->email = $postVars['email'];
    $obPaciente->contato = $postVars['contato'];
    $obPaciente->genero = $postVars['genero'];
    $obPaciente->situacao = $postVars['situacao'];
    $obPaciente->cadastrar();

    /* RETORNA PARA PÁGINA DE EDIÇÃO */
    $request->getRouter()->redirect('/admin/pacientes/' . $obPaciente->id . '/edit?status=created');
  }


  /**
   * Método responsável por retornar o formulário de editação de um PACIENTE
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getEditPaciente($request, $id)
  {

    /* OBTEM PACIENTE NO BANCO DE DADOS */
    $obPaciente = EntityPaciente::getPacienteById($id);

    /* CASO NÃO EXISTA */
    if (!$obPaciente instanceof EntityPaciente) {
      $request->getRouter()->redirect('/admin/pacientes');
    }

    $generos = '';
    $currentModule = $obPaciente->genero;

    /* ITERA OS MODULOS DE GENEROS */
    foreach (self::$generoModules as $hash => $module) {
      $generos .= View::render('admin/modules/pacientes/generos', [
        'id' => $module['id'],
        'label' => $module['label'],
        'value' => $module['value'],
        'current' => $hash == $currentModule ? 'checked' : ''
      ]);
    }

    $situacoes = '';
    $currentSituacao = $obPaciente->situacao;

    /* ITERA OS MODULOS DE SITUAÇÕES */
    foreach (self::$situacaoModules as $hash => $module) {
      $situacoes .= View::render('admin/modules/pacientes/situacoes', [
        'id' => $module['id'],
        'value' => $module['value'],
        'current' => $hash == $currentSituacao ? 'checked' : ''
      ]);
    }

    /* CONTEUDO DO FORMULÁRIO */
    $content = View::render('admin/modules/pacientes/form', [
      'title' => 'Editar Paciente',
      'nome' => $obPaciente->nome,
      'email' => $obPaciente->email,
      'contato' => $obPaciente->contato,
      'generos' => $generos,
      'situacoes' => $situacoes,
      'status' => self::getStatus($request)
    ]);

    /* RETORNA A PÁGINA */
    return parent::getPanel('Editar paciente > GMFARM', $content, 'pacientes');
  }

  /**
   * Método responsável por gravar a atualização de um PACIENTE
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setEditPaciente($request, $id)
  {

    /* OBTEM PACIENTE NO BANCO DE DADOS */
    $obPaciente = EntityPaciente::getPacienteById($id);

    /* CASO NÃO EXISTA */
    if (!$obPaciente instanceof EntityPaciente) {
      $request->getRouter()->redirect('/admin/pacientes');
    }

    /* POST VARS */
    $postVars = $request->getPostVars();

    /* ATUALIZA A INSTANCIA */
    $obPaciente->nome = $postVars['nome'] ?? $obPaciente->nome;
    $obPaciente->email = $postVars['email'] ?? $obPaciente->email;
    $obPaciente->contato = $postVars['contato'] ?? $obPaciente->contato;
    $obPaciente->genero = $postVars['genero'] ?? $obPaciente->genero;
    $obPaciente->situacao = $postVars['situacao'] ?? $obPaciente->situacao;
    $obPaciente->atualizar();

    /* REDIRECIONA O USUARIO */
    $request->getRouter()->redirect('/admin/pacientes/' . $obPaciente->id . '/edit?status=updated');
  }

  /**
   * Método responsável por retornar o formulário de exclusão de um paciente
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getDeletePaciente($request, $id)
  {

    /* OBTEM PACIENTE DO BANCO DE DADOS */
    $obPaciente = EntityPaciente::getPacienteById($id);

    /* CASO NÃO EXISTA */
    if (!$obPaciente instanceof EntityPaciente) {
      $request->getRouter()->redirect('/admin/pacientes');
    }

    /* CONTEUDO DO FORMULÁRIO */
    $content = View::render('admin/modules/pacientes/delete', [
      'id' => $obPaciente->id,
      'title' => 'Deletar Paciente',
      'nome' => $obPaciente->nome
    ]);

    /* RETORNA A PÁGINA */
    return parent::getPanel('Deletar paciente > GMFARM', $content, 'pacientes');
  }

  /**
   * Método responsável por fazer a exclusão de um aluno
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setDeletePaciente($request, $id)
  {

    /* OBTEM MATERIAL NO BANCO DE DADOS */
    $obPaciente = EntityPaciente::getPacienteById($id);

    /* CASO NÃO EXISTA */
    if (!$obPaciente instanceof EntityPaciente) {
      $request->getRouter()->redirect('/admin/pacientes');
    }

    /* EXCLUI O MATERIAL */
    $obPaciente->excluir();

    /* REDIRECIONA O USUARIO */
    $request->getRouter()->redirect('/admin/pacientes?status=deleted');
  }

  /**
   * Método responsável por obter a renderização dos atributos do paciente 
   * @param Request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getPacienteAtributos($request, &$obPagination)
  {
    /* PACIENTES */
    $atributos = '';

    /* Quantidade total de registros */
    $quantidadeTotal = EntityPaciente::getAtributos(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

    /* Página atual */
    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;

    /* Instancia de paginação */
    $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 20);

    /* Resultados da página */
    $results = EntityPaciente::getAtributos(null, 'id ASC', $obPagination->getLimit());

    /* Renderiza o atributo */
    while ($obPaciente = $results->fetchObject(EntityPaciente::class)) {
      $atributos .= View::render('admin/modules/pacientes/atributo', [
        'id' => $obPaciente->id,
        'nome' => $obPaciente->nome,
        'email' => $obPaciente->email,
        'contato' => $obPaciente->contato,
        'genero' => $obPaciente->genero,
        'situacao' => $obPaciente->situacao
      ]);
    }

    /* RETORNA OS ALUNOS */
    return $atributos;
  }

  /**
   * Método responsável por renderizar a view de Materias
   * @return string
   */
  public static function getPaciente($request)
  {

    /* CONTEÚDO DA HOME */
    $content = View::render('admin/modules/pacientes/index', [
      'atributos' => self::getPacienteAtributos($request, $obPagination),
      'pagination' => parent::getPagination($request, $obPagination),
      'status' => self::getStatus($request)
    ]);

    /* RETORNA A PÁGINA COMPLETA */
    return parent::getPanel('Pacientes > GMFARM', $content, 'pacientes');
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
        return Alert::getSuccess('Paciente cadastrado com sucesso!');
        break;
      case 'updated':
        return Alert::getSuccess('Dados do paciente atualizados com sucesso!');
        break;
      case 'deleted':
        return Alert::getSuccess('Paciente deletado com sucesso!');
        break;
    }
  }
}
