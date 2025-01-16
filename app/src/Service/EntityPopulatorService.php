<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;

class EntityPopulatorService
{
    public function populate(array $array = []): ?Book
    {
        if (!isset($array['author']) && !isset($array['name'])) {
            return null;
        }

        $author = new Author();
        $author->setName($array['author']);
        $book = new Book();
        $book->setName($array['name']);
        $book->setAuthor($author);

        return $book;
    }
}