<?php

namespace App\Model\Entity;

use \Rubensdimas\DatabaseManager\Database;

class Paciente
{

  public $id;

  public $nome;

  public $email;

  public $contato;

  public $genero;

  public $situacao;

  public function cadastrar()
  {
    /* INSERE NO BANCO DE DADOS */
    $this->id = (new Database)->insert('paciente',[
      'nome' => $this->nome,
      'email' => $this->email,
      'contato' => $this->contato,
      'genero' => $this->genero,
      'situacao' => $this->situacao
    ]);

    /* Sucesso */
    return true;
  }

  public function atualizar()
  {
    /* ATUALIZA NO BANCO DE DADOS */
    return (new Database)->update('paciente','id = ' . $this->id, [
      'nome' => $this->nome,
      'email' => $this->email,
      'contato' => $this->contato,
      'genero' => $this->genero,
      'situacao' => $this->situacao,
    ]);
  }

  public function excluir()
  {
    /* EXCLUI O PACIENTE NO BANCO DE DADOS */
    return (new Database)->delete('paciente','id = ' . $this->id);
  }

  /**
   * Método responsável por retornar (atributos do paciente)
   * @param string $where
   * @param string $order
   * @param string $limit
   * @param string $fields
   * @return PDOStatement 
   */
  public static function getAtributos($where = null, $order = null, $limit = null, $fields = '*')
  {
    return (new Database)->select('paciente',$where, $order, $limit, $fields);
  }

  /**
   * Método responsável por retornar o paciente com base no ID
   * @param integer $id_material
   * @return Paciente
   */
  public static function getPacienteById($id)
  {
    return self::getAtributos('id = ' . $id)->fetchObject(self::class);
  }
}
