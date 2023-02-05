<?php

namespace App\Model\Entity;

use \Rubensdimas\RedisManager\Redis;
use \Rubensdimas\DatabaseManager\Database;

class User
{
  public $id;

  public $nome;

  public $email;

  public $matricula;

  public $senha;

  public function cadastrar()
  {
    /* INSERE NO BANCO DE DADOS */
    $this->id = (new Database)->insert('usuarios', [
      'nome' => $this->nome,
      'email' => $this->email,
      'matricula' => $this->matricula,
      'senha' => $this->senha
    ]);

    /* Sucesso */
    return true;
  }

  public function atualizar()
  {
    /* ATUALIZA NO BANCO DE DADOS */
    return (new Database)->update('usuarios', 'id = ' . $this->id, [
      'nome' => $this->nome,
      'email' => $this->email,
      'matricula' => $this->matricula,
      'senha' => $this->senha
    ]);
  }

  public function excluir()
  {
    /* EXCLUI O ALUNO NO BANCO DE DADOS */
    return (new Database)->delete('usuarios', 'id = ' . $this->id);
  }


  /**
   * Método responsável por retornar um usuári com base em seu e-mail
   * @param string $email
   * @return User
   */
  public static function getUserByEmail($email)
  {
    return (new Database)->select('usuarios', 'email = "' . $email . '"')->fetchObject(self::class);
  }

  /**
   * Método responsável por retornar o aluno com base no ID
   * @param integer $id_material
   * @return User
   */
  public static function getAlunoById($id)
  {
    return self::getAtributos('id = ' . $id)->fetchObject(self::class);
  }

  /**
   * Método responsável por retornar (atributos do aluno)
   * @param string $where
   * @param string $order
   * @param string $limit
   * @param string $fields
   * @return PDOStatement 
   */
  public static function getAtributos($where = null, $order = null, $limit = null, $fields = '*')
  {
    return (new Database)->select('usuarios', $where, $order, $limit, $fields);
  }

  /**
   * Método responsável por retornar atributos do aluno vindo do banco de dados ou cache
   * @param integer $id
   * @return array
   */
  public static function getDataOrCache($id){
    $obRedis = new Redis;
    return $obRedis->getByKeyAndField('alunos','aluno'.$id) === null ? self::getAlunoById($id) : $obRedis->getByKeyAndField('alunos','aluno'.$id);
  }

  public static function getAllDataOrCache($obPagination){
    $obRedis = new Redis;
    $arr = [];

    if($obRedis->getAll('alunos') === null){
      
      $results = self::getAtributos('tipo_usuario = "aluno"', 'id desc', $obPagination->getLimit(), ' id, nome, email, matricula');

      echo "From Database <br>";
      while($obUser = $results->fetchObject(self::class)){
        $obRedis->insert('alunos', 'aluno'.$obUser->id, [
          'id' => $obUser->id,
          'nome' => $obUser->nome,
          'email' => $obUser->email,
          'matricula' => $obUser->matricula
        ]);
        $arr[] = [
          'id' => $obUser->id,
          'nome' => $obUser->nome,
          'email' => $obUser->email,
          'matricula' => $obUser->matricula
        ];
      }
      
    }else{
      echo "From Redis <br>";
      $arr = $obRedis->getAll('alunos');
    }

    /**
     * ORDENAÇÃO SEMPRE DO MAIOR ID
     */
    uasort($arr, function ($a,$b){
      return $a['id'] < $b['id'];
    });

    return $arr;

  }
}
