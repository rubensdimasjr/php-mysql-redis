<?php

namespace App\Http;

class Response
{
  /**
   * Código do status do http
   * @var integer
   */
  private $httpCode = 200; //

  /**
   * Cabeçalho do response
   * @var array
   */
  private $headers = [];

  /**
   * Tipo de conteúdo que está sendo retornado
   * @var string 
   */
  private $contentType = 'text/html';

  /**
   * Conteúdo do Response
   * @var mixed
   */
  private $content;

  /**
   * Método responsavel por iniciar a classe e definir os seus valores
   * @param integer $httpCode
   * @param mixed $content
   * @param string $contentType
   */
  public function __construct($httpCode, $content, $contentType = 'text/html')
  {
    $this->httpCode = $httpCode;
    $this->content = $content;
    $this->setContentType($contentType);
  }

  /**
   * Método responsavel por alterar o contentType do response
   * @param string $contentType
   */
  public function setContentType($contentType)
  {
    $this->contentType = $contentType;
    $this->addHeader('Content-Type', $contentType);
  }

  /**
   * Método responsavel por adicionar o registro no cabeçalho de response
   * @param string $key
   * @param string $value
   */
  public function addHeader($key, $value)
  {
    $this->headers[$key] = $value;
  }

  /**
   * Método responsavel por enviar os headers para o navegador
   */
  private function sendHeaders()
  {
    /* STATUS */
    http_response_code($this->httpCode);

    /* ENVIAR HEADERS */
    foreach ($this->headers as $key => $value) {
      header($key . ': ' . $value);
    }
  }

  /**
   * Método responsavel por enviar response para o usuário
   */
  public function sendResponse()
  {
    /* ENVIA OS HEADERS */
    $this->sendHeaders();

    /* IMPRIME O CONTEÚDO */
    switch ($this->contentType) {
      case 'text/html':
        echo $this->content;
        exit;
    }
  }
}
