<?php
    # Defined routes with its controllers
    $__routesControllerMap = [
        '/public' => ['App\Controller\HomeController', 'index'],
    ];

    # Generates base url. Will use this on templates and controllers
    #$__baseUrl = "http://" . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'];
    $__baseUrl = "http://localhost:" . $_SERVER['SERVER_PORT'];

    # Explodes request uri
    $__urlArray = explode('/', $_SERVER['REQUEST_URI']);
    $__urlId = null;

    /**
     * The purpose of this part is to identify the dynamic value of the request URI. We grab that value as controller's
     * parameter ($__urlId) then replace it by {id} to match the $__routesControllerMap's keys and use its specific
     * controller.
     *
     * To get that value, we iterate $__routesControllerMap, explode each of it into array ($routeMap), then compare its
     * size to $__urlArray (exploded request URI), then we map the {id} part on the request URL from $routeMap.
     */
    foreach ($__routesControllerMap as $key => $value) {
        $routeMap = explode('/', $key);

        # Checking it's sizes
        if (sizeof($__urlArray) == sizeof($routeMap)) {
            # Compare the end part of the URI
            if (end($__urlArray) != end($routeMap)) {
                # Request URL is /public/task/{id}
                if (end($routeMap) == '{id}') {
                    $__urlId = $__urlArray[sizeof($__urlArray) - 1];
                    $__urlArray[sizeof($__urlArray) - 1] = '{id}';
                    break;
                }
            } else {
                # Request URL is /public/task/{id}/delete
                if (end($routeMap) == 'delete' && $routeMap[sizeof($routeMap) - 2] == '{id}') {
                    $__urlId = $__urlArray[sizeof($__urlArray) - 2];
                    $__urlArray[sizeof($__urlArray) - 2] = '{id}';
                    break;
                }
                # Request URL is /public/task/new
                break;
            }
        }
    }

    /**
     * Implodes back to string but this time the dynamic part of the url is now replaced by {id}. This ensures to match
     * one of the $__routesControllerMap's keys
     */
    $__requestUri = implode('/', $__urlArray);

    # Throws exception if request uri is not in the $__routesControllerMap's keys
    if (!array_key_exists($__requestUri, $__routesControllerMap))
        throw new ErrorException('Route not found');

    # Calls the route's controller
    $__render = call_user_func($__routesControllerMap[$__requestUri], $__urlId);
