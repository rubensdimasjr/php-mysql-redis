<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class Dados
{

  public $estoque;
  public $usuarios;
  public $paciente;

  /**
   * Método responsável por retornar dados estatisticos
   */
  public static function getDados($where = null, $order = null, $limit = null, $fields = '*')
  {
    return (new Database)->selectStats($where, $order, $limit, $fields);
  }
}
