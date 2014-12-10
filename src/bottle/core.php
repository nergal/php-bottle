<?php

/**
 * The main Bottle class, loading modules
 *
 * @package Bottle
 * @author Nergal
 */
class Bottle_Core {
    /**
     * Routing and controller initialization
     *
     * @static
     * @return void
     */
    public static function start()
    {
        global $request, $response;
        // TODO: move to method?
        $request = new Bottle_Request;
        $response = new Bottle_Response;

        $functions = get_defined_functions();
        $controllers = $functions['user'];
        $controllers_list = array();
        $views_list = array();
        foreach($controllers as $controller) {
            if (substr($controller, 0, 2) != '__') {
                $controller = new ReflectionFunction($controller);

                if ($controller->isUserDefined()) {
                    $docline = $controller->getDocComment();

                    if (preg_match('#^( |\t)*\*( )?@route (?P<route>.+?)$#umsi', $docline, $matches)) {

                        $route = new Bottle_Route($controller->getName());
                        $route->setMask($matches['route']);
                        $route->bindController($controller);

                        $controllers_list[$controller->getName()] = $route->getMask();


                        if ($route->isServed($request->uri())) {
                            if (preg_match('#^( |\t)*\*( )?@view (?P<view>.+?)$#umsi', $docline, $matches)) {
                                $view = new Bottle_View($matches['view']);
                                $response->setView($view);
                                $views_list[] = $view;
                            }

                            $request->setRouter($route);
                            //break;
                        } else {
                            // fetching all views for the url() function
                            if (preg_match('#^( |\t)*\*( )?@view (?P<view>.+?)$#umsi', $docline, $matches)) {
                                $view = new Bottle_View($matches['view']);
                                $views_list[] = $view;
                            }
                        }
                    }
                }
            }
        }

        // giving the route list to each view
        foreach($views_list as $view) {

            $view->setRoutes($controllers_list);
        }


        $response->dispatch($request);
    }
}
