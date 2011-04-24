<?php

/**
 * Основной класс для подгрузки модулей
 *
 * @package Bottle
 * @author Nergal
 */
class Bottle_Core {
    /**
     * Инициализация роутинга и контроллеров
     *
     * @static
     * @return void
     */
    public static function start()
    {
        $request = new Bottle_Request;
        $response = new Bottle_Response;

        $functions = get_defined_functions();
        $controllers = $functions['user'];
        foreach($controllers as $controller) {
            if (substr($controller, 0, 2) != '__') {
                $controller = new ReflectionFunction($controller);

                if ($controller->isUserDefined()) {
                    $docline = $controller->getDocComment();

                    if (preg_match('#^( |\t)*\*( )?@route (?P<route>.+?)$#umsi', $docline, $matches)) {

                        $route = new Bottle_Route($controller->getName());
                        $route->setMask($matches['route']);
                        $route->bindController($controller);

                        if ($route->isServed($request->uri())) {
                            $request->setRouter($route);
                            break;
                        }
                    }
                }
            }
        }

        $response->dispatch($request);
    }
}
