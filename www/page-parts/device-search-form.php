<form class="d-flex" role="search" method="get" action="index.php#devices">
    <input
        class="form-control me-2"
        type="search"
        name="search-term"
        <?php
        if ($is_searching_by_term) {
            echo "value='{$search_term}'";
        }
        ?>
        placeholder="Search"
        aria-label="Search">
    <button class="btn btn-secondary" type="submit">Search</button>
</form>