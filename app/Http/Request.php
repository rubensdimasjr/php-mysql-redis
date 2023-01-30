<?php

namespace App\Http;

class Request
{

  /** Instância do Router
   * @var Router
   */
  private $router;

  /**
   * Método Http da requisição
   * @var string
   */
  private $httpMethod;

  /**
   * URI da página
   * @var string
   */
  private $uri;

  /**
   * Parameters da URI ($_GET)
   * @var array
   */
  private $queryParams = [];

  /**
   * Variaveis recebidas no post da página
   * @var array
   */
  private $postVars = [];

  /**
   * Cabeçalho da requisição
   * @var array
   */
  private $headers = [];

  /**
   * Construtor da classe
   */
  public function __construct($router)
  {
    $this->router = $router;
    $this->queryParams = $_GET ?? [];
    $this->postVars = $_POST ?? [];
    $this->headers = getallheaders();
    $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
    $this->setUri();
  }

  /**
   * Método responsável por definir a URI
   */
  private function setUri()
  {

    /* URI COMPLETA (COM GETS) */
    $this->uri = $_SERVER['REQUEST_URI'] ?? '';

    /* REMOVE GETS DA URI */
    $xUri = explode('?', $this->uri);
    $this->uri = $xUri[0];
  }

  /**
   * Método responsável por retornar a instancia de router
   * @return Router
   */
  public function getRouter()
  {
    return $this->router;
  }

  /**
   * Método responsavel por retornar o método http da requisição
   * @return string
   */
  public function getHttpMethod()
  {
    return $this->httpMethod;
  }

  /**
   * Método responsavel por retornar a URI da requisição
   * @return string
   */
  public function getUri()
  {
    return $this->uri;
  }

  /**
   * Método responsavel por retornar os headers da página
   * @return array
   */
  public function getHeaders()
  {
    return $this->headers;
  }

  /**
   * Método responsavel por retornar os parametros da URL da requisição
   * @return array
   */
  public function getQueryParams()
  {
    return $this->queryParams;
  }

  /**
   * Método responsavel por retornar as variaveis POST da requisição
   * @return array
   */
  public function getPostVars()
  {
    return $this->postVars;
  }
}
