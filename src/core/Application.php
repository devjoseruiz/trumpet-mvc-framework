<?php

namespace app\core;

class Application
{
    public static string $ROOT_DIR;
    public static Application $app;
    public Request $request;
    public Response $response;
    public Router $router;
    public Controller $controller;
    public Database $db;

    public function __construct(string $rootPath, array $config)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
    }

    public function getController(): Controller
    {
        return $this->controller;
    }

    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function run()
    {
        echo $this->router->resolve();
    }
}