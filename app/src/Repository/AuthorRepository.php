<?php

namespace App\Repository;

use App\Entity\Author;
use Framework\Database\Database;
use Framework\Service\RepositoryInterface;

/**
 * This is where queries on task table are stored.
 */
class AuthorRepository implements RepositoryInterface
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
     * Get all authors
     *
     * @param array $orderBy
     * @param int|null $limit
     * @return array
     */
    public function findAll(array $orderBy = [], int $limit = null): array
    {
        $query = 'SELECT * FROM authors';

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

        $stmt = $this->database->query($query);
        $results = $stmt->fetchAll(\PDO::FETCH_CLASS);
        $authors = [];

        foreach ($results as $result) {
            $author = new Author();
            $author->setId($result->id);
            $author->setName($result->name);
            $authors[] = $author;
        }

        return $authors;
    }

    /**
     * Get one author by field
     *
     * @param array $field
     * @return Author
     * @throws \ErrorException
     */
    public function findOneBy(array $field = []): ?Author
    {
        if (!$field)
            throw new \ErrorException('field in findBy is required');

        $query = 'SELECT * FROM authors WHERE';

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

        $author = new Author();
        $author->setId($row[0]->id);
        $author->setName($row[0]->name);

        return $author;
    }

    /**
     * Create an author
     *
     * @param Author|null $author
     */
    public function create(Author $author = null)
    {
        $query = "INSERT INTO authors (name) VALUES (:authorName)";
        $this->database->prepareAndExecute($query, ['authorName' => $author->getName()]);
    }

    /**
     * Update an author
     *
     * @param Author|null $author
     */
    public function update(Author $author = null)
    {
        $query = "UPDATE authors SET name = '" . $author->getName() . "'";
        $this->database->exec($query);
    }

    /**
     * Delete an author
     *
     * @param Author|null $author
     */
    public function delete(Author $author = null)
    {
    }
}