<div class="w-80 mx-auto results">
    <h1>TransferMate - Exam</h1>
    <div class="search-wrapper mb-2">
        <form action="" method="post" class="inline">
            <input type="text" id="author_name" name="author_name" placeholder="Search Author" value="<?php echo $_POST['author_name'] ?: ''; ?>">
            <button type="submit">Search</button>
        </form>
        <button onclick="document.getElementById('author_name').value = '';" class="inline">Clear</button>
    </div>
    <div id="books-list">
        <?php if (!$books) { ?>
            <div class="w-100 b-1 p-1">No records...</div>
        <?php } else {
            foreach ($books as $book) { ?>
                <div class="w-100 slide-animate">
                    <div class="b-1 p-1 w-50 inline">
                        <?php echo $book->getAuthor()->getName(); ?>
                    </div>
                    <div class="b-1 p-1 w-50 inline">
                        <?php echo $book->getName() ? $book->getName() : '&lt;none&gt; (no books found)'; ?>
                    </div>
                </div>
            <?php }} ?>
    </div>
<!--    <div id="pagination">-->
<!--        <span>Total Records: --><?php //echo $pagination->getRecordCount(); ?><!--</span>-->
<!--        <span>Total Pages: --><?php //echo $pagination->getTotalPages(); ?><!--</span>-->
<!--        <div class="page-wrap">-->
<!--            --><?php //for($page=1; $page<=$pagination->getTotalPages(); $page++) { ?>
<!--                <div class="p-1 mr-1 inline">-->
<!--                --><?php //if ($page == $pagination->getSelectedPage) {
//                    echo $page;
//                } else { ?>
<!--                    <a href="index.html.php&page=--><?php //echo $page; ?><!--">--><?php //echo $page; ?><!--</a>-->
<!--                --><?php //} ?>
<!--                </div>-->
<!--            --><?php //} ?>
<!--        </div>-->
<!--    </div>-->
</div>