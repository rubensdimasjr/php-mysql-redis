<?php

namespace App\Controller\Admin;

use \App\Model\Entity\Estoque as EntityEstoque;
use \Rubensdimas\DatabaseManager\Pagination;

use \App\Utils\View;

class Material extends Page
{

  /**
   * Método responsável por obter a renderização dos itens de estoque 
   * @param Request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getEstoqueItems($request, &$obPagination)
  {
    /* MATERIAIS */
    $itens = '';

    /* Quantidade toral de registros */
    $quantidadeTotal = EntityEstoque::getItems(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

    /* Página atual */
    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;

    /* Instancia de paginação */
    $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 20);

    /* Resultados da página */
    $results = EntityEstoque::getItems(null, 'id_material DESC', $obPagination->getLimit());

    /* Renderiza o item */
    while ($obEstoque = $results->fetchObject(EntityEstoque::class)) {
      $itens .= View::render('admin/modules/materiais/item', [
        'id_material' => $obEstoque->id_material,
        'reagente' => $obEstoque->reagente,
        'lote' => $obEstoque->lote,
        'fabricante' => $obEstoque->fabricante,
        'fabricacao' => date('m/Y', strtotime($obEstoque->fabricacao)),
        'validade' => date('m/Y', strtotime($obEstoque->validade)),
        'quantidade' => $obEstoque->quantidade,
        'embalagem_original' => $obEstoque->embalagem_original,
        'cas' => $obEstoque->cas,
      ]);
    }

    /* RETORNA OS ITENS */
    return $itens;
  }

  /**
   * Método responsável por renderizar a view de Materias
   * @return string
   */
  public static function getMaterial($request)
  {

    /* CONTEÚDO DA HOME */
    $content = View::render('admin/modules/materiais/index', [
      'itens' => self::getEstoqueItems($request, $obPagination),
      'pagination' => parent::getPagination($request, $obPagination),
      'status' => self::getStatus($request)
    ]);

    /* RETORNA A PÁGINA COMPLETA */
    return parent::getPanel('Materiais > GMFARM', $content, 'materiais');
  }

  /**
   * Método responsável por cadastrar um material no banco
   * @param Request
   * @return string
   */
  public static function setNewMaterial($request)
  {

    /* DADOS DO POST */
    $postVars = $request->getPostVars();

    /* NOVA INSTANCIA DE ESTOQUE */
    $obMaterial = new EntityEstoque;
    $obMaterial->reagente = $postVars['reagente'];
    $obMaterial->lote = $postVars['lote'];
    $obMaterial->fabricante = $postVars['fabricante'];
    $obMaterial->fabricacao = $postVars['fabricacao'];
    $obMaterial->validade = $postVars['validade'];
    $obMaterial->quantidade = $postVars['quantidade'];
    $obMaterial->embalagem_original = $postVars['embalagem_original'];
    $obMaterial->cas = $postVars['cas'];
    $obMaterial->cadastrar();

    /* RETORNA PARA PÁGINA DE EDIÇÃO */
    $request->getRouter()->redirect('/admin/materiais/' . $obMaterial->id_material . '/edit?status=created');
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
        return Alert::getSuccess('Material criado com sucesso!');
        break;
      case 'updated':
        return Alert::getSuccess('Material atualizado com sucesso!');
        break;
      case 'deleted':
        return Alert::getSuccess('Material deletado com sucesso!');
        break;
    }
  }

  /**
   * Método responsável por retornar o formulário de editação de um material
   * @param Request $request
   * @param integer $id_material
   * @return string
   */
  public static function getEditMaterial($request, $id_material)
  {

    /* OBTEM MATERIAL NO BANCO DE DADOS */
    $obMaterial = EntityEstoque::getMaterialById($id_material);

    /* CASO NÃO EXISTA */
    if (!$obMaterial instanceof EntityEstoque) {
      $request->getRouter()->redirect('/admin/materiais');
    }

    /* CONTEUDO DO FORMULÁRIO */
    $content = View::render('admin/modules/materiais/form', [
      'title' => 'Editar Material',
      'reagente' => $obMaterial->reagente,
      'lote' => $obMaterial->lote,
      'fabricante' => $obMaterial->fabricante,
      'fabricacao' => $obMaterial->fabricacao,
      'validade' => $obMaterial->validade,
      'quantidade' => $obMaterial->quantidade,
      'embalagem_original1' => $obMaterial->embalagem_original == "Sim" ? 'checked' : '',
      'embalagem_original2' => $obMaterial->embalagem_original == "Não" ? 'checked' : '',
      'cas' => $obMaterial->cas,
      'status' => self::getStatus($request)
    ]);

    /* RETORNA A PÁGINA */
    return parent::getPanel('Editar material > GMFARM', $content, 'materiais');
  }

  /**
   * Método responsável por gravar a atualização de um material
   * @param Request $request
   * @param integer $id_material
   * @return string
   */
  public static function setEditMaterial($request, $id_material)
  {

    /* OBTEM MATERIAL NO BANCO DE DADOS */
    $obMaterial = EntityEstoque::getMaterialById($id_material);

    /* CASO NÃO EXISTA */
    if (!$obMaterial instanceof EntityEstoque) {
      $request->getRouter()->redirect('/admin/materiais');
    }

    /* POST VARS */
    $postVars = $request->getPostVars();

    /* ATUALIZA A INSTANCIA */
    $obMaterial->reagente = $postVars['reagente'] ?? $obMaterial->reagente;
    $obMaterial->lote = $postVars['lote'] ?? $obMaterial->lote;
    $obMaterial->fabricante = $postVars['fabricante'] ?? $obMaterial->fabricante;
    $obMaterial->fabricacao = $postVars['fabricacao'] ?? $obMaterial->fabricacao;
    $obMaterial->validade = $postVars['validade'] ?? $obMaterial->validade;
    $obMaterial->embalagem_original = $postVars['embalagem_original'] ?? $obMaterial->embalagem_original;
    $obMaterial->quantidade = $postVars['quantidade'] ?? $obMaterial->quantidade;
    $obMaterial->cas = $postVars['cas'] ?? $obMaterial->cas;

    $obMaterial->atualizar();

    /* REDIRECIONA O USUARIO */
    $request->getRouter()->redirect('/admin/materiais/' . $obMaterial->id_material . '/edit?status=updated');
  }

  /**
   * Método responsável por retornar o formulário de exclusão de um material
   * @param Request $request
   * @param integer $id_material
   * @return string
   */
  public static function getDeleteMaterial($request, $id_material)
  {

    /* OBTEM MATERIAL NO BANCO DE DADOS */
    $obMaterial = EntityEstoque::getMaterialById($id_material);

    /* CASO NÃO EXISTA */
    if (!$obMaterial instanceof EntityEstoque) {
      $request->getRouter()->redirect('/admin/materiais');
    }

    /* CONTEUDO DO FORMULÁRIO */
    $content = View::render('admin/modules/materiais/delete', [
      'id_material' => $obMaterial->id_material,
      'title' => 'Deletar Material',
      'cas' => $obMaterial->cas
    ]);

    /* RETORNA A PÁGINA */
    return parent::getPanel('Deletar material > GMFARM', $content, 'materiais');
  }

  /**
   * Método responsável por fazer a exclusão de um material
   * @param Request $request
   * @param integer $id_material
   * @return string
   */
  public static function setDeleteMaterial($request, $id_material)
  {

    /* OBTEM MATERIAL NO BANCO DE DADOS */
    $obMaterial = EntityEstoque::getMaterialById($id_material);

    /* CASO NÃO EXISTA */
    if (!$obMaterial instanceof EntityEstoque) {
      $request->getRouter()->redirect('/admin/materiais');
    }

    /* EXCLUI O MATERIAL */
    $obMaterial->excluir();

    /* REDIRECIONA O USUARIO */
    $request->getRouter()->redirect('/admin/materiais?status=deleted');
  }
}
