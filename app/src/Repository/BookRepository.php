<?php

namespace App\Repository;

use App\Entity\Author;
use App\Entity\Book;
use Framework\Database\Database;
use Framework\Service\RepositoryInterface;

/**
 * This is where queries on task table are stored.
 */
class BookRepository implements RepositoryInterface
{
    private $database;

    /**
     * Constructor with Dependency Injection
     * Reference: https://www.getopensocial.com/blog/open-source/dependency-injection-php/
     *
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Get all books
     *
     * @param array $orderBy
     * @param int|null $limit
     * @return array
     */
    public function findAll(array $orderBy = [], int $limit = null): array
    {
        $query = 'SELECT * FROM books';

        # Adds order by statement in query string
        if ($orderBy) {
            $query .= ' ORDER BY';

            foreach ($orderBy as $key => $value) {
                $query .= ' ' . $key . ' ' . $value . ',';
            }

            # Removes trailing ","
            $query = rtrim($query, ',');
        }

        # Adds order by statement in query string
        if ($limit) {
            $query .= ' LIMIT ' . $limit;
        }

        $results = $this->database->query($query);
        $books = [];

        while ($row = $results->fetchArray()) {
            $book = new Book();
            $book->setId($row['id']);
            $book->setName($row['name']);
            $book->setAuthorId($row['author_id']);
            $books[] = $book;
        }

        return $books;
    }

    public function findAllBooksWithAuthor(array $orderBy = [], int $limit = null): array
    {
        $queryTemplate = '
            SELECT :select
            FROM authors a
            LEFT JOIN books b
            ON a.id = b.author_id
        ';

        $query = str_replace(':select', 'a.id as author_id, a.name as author_name, b.id as book_id, b.name as book_name', $queryTemplate);
        $query = $this->addOrderByAndLimitStatement($query, $orderBy, $limit);
        $stmt = $this->database->query($query);
        $results = $stmt->fetchAll(\PDO::FETCH_CLASS);

        return $this->generateBookObjectsFromResults($results);
    }

    public function findBooksByAuthorName($authorName, array $orderBy = [], $limit = null): array
    {
        $queryTemplate = '
            SELECT :select
            FROM authors a 
            LEFT JOIN books b 
            ON a.id = b.author_id
            WHERE a.name = :author_name
        ';

        $query = str_replace(':select', 'a.id as author_id, a.name as author_name, b.id as book_id, b.name as book_name', $queryTemplate);
        $query = $this->addOrderByAndLimitStatement($query, $orderBy, $limit);
        $stmt = $this->database->prepareAndExecute($query, ['author_name' => $authorName]);
        $results = $stmt->fetchAll(\PDO::FETCH_CLASS);

        return $this->generateBookObjectsFromResults($results);
    }

    private function generateBookObjectsFromResults(Array $results): array
    {
        $books = [];

        foreach ($results as $result) {
            $book = new Book();
            $book->setId($result->book_id);
            $book->setName($result->book_name);

            $author = new Author();
            $author->setId($result->author_id);
            $author->setName($result->author_name);

            $book->setAuthorId($author->getId());
            $book->setAuthor($author);

            $books[] = $book;
        }

        return $books;
    }

    private function addOrderByAndLimitStatement($query, array $orderBy = [], int $limit = null): String
    {
        # Adds order by statement in query string
        if ($orderBy) {
            $query .= ' ORDER BY';

            foreach ($orderBy as $key => $value) {
                $query .= ' ' . $key . ' ' . $value . ',';
            }

            # Removes trailing ","
            $query = rtrim($query, ',');
        }

        # Adds order by statement in query string
        if ($limit) {
            $query .= ' LIMIT ' . $limit;
        }

        return $query;
    }

    /**
     * Get one book by field
     *
     * @param array $field
     * @return Book
     * @throws \ErrorException
     */
    public function findOneBy(array $field = []): ?Book
    {
        if (!$field)
            throw new \ErrorException('field in findBy is required');

        $query = 'SELECT * FROM books WHERE';

        foreach ($field as $key => $value) {
            $query .= " " . $key . " = '" . $value . "' AND ";
        }

        $query = rtrim($query, ' AND ');
        $query .= ' LIMIT 1';
        $results = $this->database->query($query);
        $row = $results->fetchAll(\PDO::FETCH_CLASS);

        if (!$row) {
            return null;
        }

        $book = new Book();
        $book->setId($row[0]->id);
        $book->setName($row[0]->name);
        $book->setAuthorId($row[0]->author_id);

        return $book;
    }

    /**
     * Create a book
     *
     * @param Book|null $book
     */
    public function create(Book $book = null)
    {
        $query = "INSERT INTO books (name, author_id) VALUES (:bookName, :authorId)";
        $this->database->prepareAndExecute($query, [
            'bookName' => $book->getName(),
            'authorId' => $book->getAuthor()->getId(),
        ]);
    }

    /**
     * Update a book
     *
     * @param Book|null $book
     */
    public function update(Book $book = null)
    {
        $query = "UPDATE books SET name = :bookName, author_id = :authorId WHERE id = :bookId";
        $this->database->prepareAndExecute($query, [
            'bookName' => $book->getName(),
            'authorId' => $book->getAuthor()->getId(),
            'bookId' => $book->getId(),
        ]);
    }

    /**
     * Delete a book
     *
     * @param Book|null $book
     */
    public function delete(Book $book = null)
    {
    }
}