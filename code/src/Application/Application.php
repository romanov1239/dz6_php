<?php

namespace Geekbrains\Application1\Application;

use Geekbrains\Application1\Infrastructure\Config;
use Geekbrains\Application1\Infrastructure\Storage;
use Geekbrains\Application1\Application\Render;

class Application
{
    private const APP_NAMESPACE = 'Geekbrains\Application1\Domain\Controllers\\';
    private string $controllerName;
    private string $methodName;
    private static array $config;
    public static Config $config1;
    public static Storage $storage;

    public function __construct(){
        Application::$config1 = new Config();
        Application::$storage = new Storage();
    }

    public static function config(): array
    {
        return Application::$config;
    }

    public function run(): string
    {
        try {
            Application::$config = parse_ini_file('config.ini', true);

            $routeArray = explode('/', $_SERVER['REQUEST_URI']);
            $controllerName = isset($routeArray[1]) && $routeArray[1] !== '' ? $routeArray[1] : "page";
            $this->controllerName = Application::APP_NAMESPACE . ucfirst($controllerName) . "Controller";

            if (class_exists($this->controllerName)) {
                $methodName = isset($routeArray[2]) && $routeArray[2] !== '' ? $routeArray[2] : "index";
                $this->methodName = "action" . ucfirst($methodName);

                if (method_exists($this->controllerName, $this->methodName)) {
                    $controllerInstance = new $this->controllerName();
                    return call_user_func_array([$controllerInstance, $this->methodName], []);
                } else {
                    return "Метод не существует";
                }
            } else {
                header('HTTP/1.1 404 Not Found', true, 404);
                include("error-page.html");
                exit;
            }
        } catch (\Throwable $e) {
            return Render::renderExceptionPage($e);
        }
    }
}
