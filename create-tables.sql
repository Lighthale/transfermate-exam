CREATE TABLE authors (
    id SMALLINT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE books (
    id SMALLINT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    name VARCHAR(255) NOT NULL,
    author_id SMALLINT
);

ALTER TABLE "books"
ADD CONSTRAINT "FK_books_authors"
FOREIGN KEY ("author_id")
REFERENCES "authors" ("id")
ON UPDATE NO ACTION
ON DELETE NO ACTION;