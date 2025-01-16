<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Framework\Controller\BaseController;
use Framework\Database\Database;

class HomeController extends BaseController
{
    /**
     * route: '/public'
     */
    public static function index()
    {
        $database = new Database();
        $bookRepository = new BookRepository($database);
        $authorName = $_POST['author_name'];

        if ($authorName) {
            $books = $bookRepository->findBooksByAuthorName($authorName, [], 10);
        } else {
            $books = $bookRepository->findAllBooksWithAuthor(['a.name' => 'ASC'], 10);
        }

        return parent::render('partial/index.html.php', [
            'books' => $books,
        ]);
    }
}
