<html>
    <ul>
        <?php
            // Connect to the db
            //retrieve the data and save it
            $items = ['item1', 'item2', 'item3']; //Get the data and inject it
            foreach ($items as $item) {
                echo <<<END
                <li>$item</li>
                END;
            }
        ?>
    </ul>
</html>
