<?php
    // $regPattern should be using regular expression
    function rsearch($folder, $regPattern) {
        $dir = new RecursiveDirectoryIterator($folder);
        $ite = new RecursiveIteratorIterator($dir);
        $files = new RegexIterator($ite, $regPattern, RegexIterator::GET_MATCH);
        $fileList = array();
        foreach($files as $file) {
            $fileList = array_merge($fileList, $file);
        }
        return $fileList;
    }

    # Includes class files
    require_once '../config/bundles.php';

    use App\Repository\AuthorRepository;
    use App\Repository\BookRepository;
    use Framework\Database\Database;

    # Connect to the database
    $db = new Database();

    # Throws error if failed database connection
    if (!$db)
        throw new ErrorException($db->lastErrorMsg());

    // usage: to find the *.xml files recursively
    $filePaths = rsearch('../startfolder', '/.*\/*\.xml/');

    libxml_use_internal_errors(TRUE);

    foreach ($filePaths as $filePath) {
        echo 'Parsing ' . $filePath . ' -- ';

        $objXmlDocument = simplexml_load_file($filePath);

        if ($objXmlDocument === FALSE) {
            echo "There were errors parsing the XML file.\n";

            foreach(libxml_get_errors() as $error) {
                echo $error->message . "\n";
            }

            continue;
        }

        $objJsonDocument = json_encode($objXmlDocument);
        $arrOutput = json_decode($objJsonDocument, TRUE);

        $entityPopulatorService = new EntityPopulatorService();
        $authorRepository = new AuthorRepository($db);
        $bookRepository = new BookRepository($db);

        $book = $entityPopulatorService->populate($arrOutput);

        if (!$book) {
            echo "Invalid XML format.\n";
            continue;
        }

        // Author object details are from XML and not from the database
        // Check if the author exist then use that existing author
        // Else add that author
        $author = $book->getAuthor();
        $existingAuthor = $authorRepository->findOneBy(['name' => $author->getName()]);

        if ($existingAuthor) {
            $author = $existingAuthor;
        } else {
            $authorRepository->create($author);
            // Query to get the ID
            $author = $authorRepository->findOneBy(['name' => $author->getName()]);
        }

        // Check if the book exist then update the book details
        $existingBook = $bookRepository->findOneBy(['name' => $book->getName()]);

        if ($existingBook) {
            $book = $existingBook;
            $book->setAuthor($author);
            $bookRepository->update($book);
        } else {
            $book->setAuthor($author);
            $bookRepository->create($book);
        }

        echo "DONE!\n";
    }
?>