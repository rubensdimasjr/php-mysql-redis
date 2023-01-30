<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Estoque
{

  public $id_material;

  public $reagente;

  public $lote;

  public $fabricante;

  public $fabricacao;

  public $validade;

  public $quantidade;

  public $embalagem_original;

  public $cas;

  public function cadastrar()
  {
    /* INSERE NO BANCO DE DADOS */
    $this->id_material = (new Database)->insert('estoque', [
      'reagente' => $this->reagente,
      'lote' => $this->lote,
      'fabricante' => $this->fabricante,
      'fabricacao' => $this->fabricacao,
      'validade' => $this->validade,
      'quantidade' => $this->quantidade,
      'embalagem_original' => $this->embalagem_original,
      'cas' => $this->cas
    ]);

    /* Sucesso */
    return true;
  }

  /**
   * Método responsável por retornar o material com base no ID
   * @param integer $id_material
   * @return Estoque
   */
  public static function getMaterialById($id_material)
  {
    return self::getItems('id_material = ' . $id_material)->fetchObject(self::class);
  }

  public function atualizar()
  {
    /* ATUALIZA NO BANCO DE DADOS */
    return (new Database)->update('estoque', 'id_material = ' . $this->id_material, [
      'reagente' => $this->reagente,
      'lote' => $this->lote,
      'fabricante' => $this->fabricante,
      'fabricacao' => $this->fabricacao,
      'validade' => $this->validade,
      'quantidade' => $this->quantidade,
      'embalagem_original' => $this->embalagem_original,
      'cas' => $this->cas
    ]);
  }

  public function excluir()
  {
    /* EXCLUI O MATERIAL NO BANCO DE DADOS */
    return (new Database)->delete('estoque', 'id_material = ' . $this->id_material);
  }


  /**
   * Método responsável por retornar (itens do estoque)
   * @param string $where
   * @param string $order
   * @param string $limit
   * @param string $fields
   * @return PDOStatement 
   */
  public static function getItems($where = null, $order = null, $limit = null, $fields = '*')
  {

    return (new Database)->select('estoque', $where, $order, $limit, $fields);
  }
}
