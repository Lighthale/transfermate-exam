<?php
    # This part is where we include all the class files

    # Framework
    require_once '../framework/Controller/ControllerTrait.php';
    require_once '../framework/Controller/BaseController.php';
    require_once '../framework/Database/Database.php';
    require_once '../framework/Service/RepositoryInterface.php';

    # Repository
    require_once '../src/Repository/AuthorRepository.php';
    require_once '../src/Repository/BookRepository.php';

    # Entities
    require_once '../src/Entity/Author.php';
    require_once '../src/Entity/Book.php';

    # Services
    require_once '../src/Service/EntityPopulatorService.php';

    # Controllers
    require_once '../src/Controller/HomeController.php';