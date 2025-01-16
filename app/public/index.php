<?php
    # Includes class files
    require_once '../config/bundles.php';

    use Framework\Database\Database;
    # Connect to the database
    $db = new Database();

    # Throws error if failed database connection
    if (!$db)
        throw new ErrorException($db->lastErrorMsg());

    /**
     * Import routes. This will determine what controller will be using depending on the URL.
     * The Controller will return a component ($__render['component']) and dynamic variables ($__render[0])
     * that will be used to render the template
     */
    require_once '../config/routes.php';

    # Getting the template to be use (HTML) from controller
    $__component = $__render['component'];
    # Exploding the controller's returned parameters into variables
    extract($__render[0]);

    # Component and extracted variables will be passed to this template
    require_once '../templates/base.html.php';