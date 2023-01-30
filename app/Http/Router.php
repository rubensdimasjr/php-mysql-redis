<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue;

class Router
{
  /**
   * URL completa do projeto (raiz)
   * @var string
   */
  private $url = '';

  /**
   * Prefixo de todas as rotas
   * @var string
   */
  private $prefix = '';

  /**
   * Indice de rotas
   * @var array
   */
  private $routes = [];

  /**
   * Instancia de request 
   * @var Request
   */
  private $request;

  /**
   * Método responsavel por iniciar a classe 
   * @param string $url
   */
  public function __construct($url)
  {
    $this->request = new Request($this);
    $this->url = $url;
    $this->setPrefix();
  }

  /**
   * Método responsável por definir o prefixo das rotas
   */
  private function setPrefix()
  {
    /* Informações da URL atual  */
    $parseUrl = parse_url($this->url);

    /* Define o prefixo */
    $this->prefix = $parseUrl['path'] ?? '';
  }

  /**
   * Método responsável por adicionar uma rota na classe
   * @param string $method
   * @param string $route
   * @param array $params
   */
  private function addRoute($method, $route, $params = [])
  {
    /* Validação dos parametros */
    foreach ($params as $key => $value) {
      if ($value instanceof Closure) {
        $params['controller'] = $value;
        unset($params[$key]);
        continue;
      }
    }

    /* Middlewares da Rota */
    $params['middlewares'] = $params['middlewares'] ?? [];

    /* Variaveis da Rota */
    $params['variables'] = [];

    /* Padrão de Validação das variaveis das rotas */
    $patternVariable = '/{(.*?)}/';
    if (preg_match_all($patternVariable, $route, $matches)) {
      $route  = preg_replace($patternVariable, '(.*?)', $route);
      $params['variables'] = $matches[1];
    }

    /* Padrão de Validação da URL */
    $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

    /* Adiciona a rota dentro da classe */
    $this->routes[$patternRoute][$method] = $params;
  }

  /**
   * Método responsável por definir uma rota de GET request
   * @param string $route
   * @param array $params
   */
  public function get($route, $params = [])
  {
    return $this->addRoute('GET', $route, $params);
  }

  /**
   * Método responsável por definir uma rota de POST request
   * @param string $route
   * @param array $params
   */
  public function post($route, $params = [])
  {
    return $this->addRoute('POST', $route, $params);
  }

  /**
   * Método responsável por definir uma rota de PUT request
   * @param string $route
   * @param array $params
   */
  public function put($route, $params = [])
  {
    return $this->addRoute('PUT', $route, $params);
  }

  /**
   * Método responsável por definir uma rota de DELETE request
   * @param string $route
   * @param array $params
   */
  public function delete($route, $params = [])
  {
    return $this->addRoute('DELETE', $route, $params);
  }

  /**
   * Método responsavel por retornar a uri desconsiderando o Prefixo
   * @return string
   */
  private function getUri()
  {
    /* URI da request */
    $uri = $this->request->getUri();

    /* Fatia a URI com o prefixo */
    $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

    /* Retorna a URI sem prefixo */
    return end($xUri);
  }

  /**
   * Método responsavel por retornar os dados da rota atual
   * @return array
   */
  private function getRoute()
  {
    /* uri */
    $uri = $this->getUri();

    /* METHOD  */
    $httpMethod = $this->request->getHttpMethod();

    /* Valida as Rotas */
    foreach ($this->routes as $patternRoute => $methods) {
      /* Verifica se a URI bate com o padrão */
      if (preg_match($patternRoute, $uri, $matches)) {
        /* Verifica o Método */
        if (isset($methods[$httpMethod])) {

          /* Remove a primeira posição */
          unset($matches[0]);

          /* Variaveis processadas */
          $keys = $methods[$httpMethod]['variables'];
          $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
          $methods[$httpMethod]['variables']['request'] = $this->request;

          /* Retorno dos parametros da rota */
          return $methods[$httpMethod];
        }

        /* Método não permitido/definido */
        throw new Exception("Método não é permitido", 405);
      }
    }

    /* URL não encontrada! */
    throw new Exception("Url não encontrada!", 404);
  }

  /**
   * Método responsavel por executar a rota atual
   * @return Response
   */
  public function run()
  {
    try {
      /* Obtem a rota atual */
      $route = $this->getRoute();

      /* Verifica o controlador */
      if (!isset($route['controller'])) {
        throw new Exception("A URL não pôde ser processada!", 500);
      }

      /* Argumentos da Função */
      $args = [];

      /* Reflection Function */
      $reflection = new ReflectionFunction($route['controller']);
      foreach ($reflection->getParameters() as $parameter) {
        $name = $parameter->getName();
        $args[$name] = $route['variables'][$name] ?? '';
      }

      /* Retorna a execução da fila de middlewares */
      return (new MiddlewareQueue($route['middlewares'], $route['controller'], $args))->next($this->request);
    } catch (Exception $e) {
      return new Response($e->getCode(), $e->getMessage());
    }
  }

  /**
   * Método responsável por retornar a url atual
   * @return string
   */
  public function getCurrentUrl()
  {
    return $this->url . $this->getUri();
  }

  /**
   * Método responsável por redirecionar a URL
   * @param string $route
   */
  public function redirect($route)
  {

    /* URL */
    $url = $this->url . $route;

    /* EXECUTA O REDIRECT */
    header('location: ' . $url);
    exit;
  }
}
