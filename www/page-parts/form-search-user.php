<form class="d-flex mb-2" role="search" method="get" action="users_management.php#users">
    <input
        class="form-control me-2"
        type="search"
        name="q"
        <?php
        if ($is_searching_by_term) {
            echo "value='{$search_term}'";
        }
        ?>
        placeholder="Search"
        aria-label="Search">
    <button class="btn btn-secondary" type="submit">Search</button>
</form>