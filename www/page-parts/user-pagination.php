<nav>
  <ul class="pagination">
    <?php 
    if ($has_first) {?>
        <li class="page-item">
            <a class="page-link" href="/users_management.php?upage=1">First</a>
        </li>
    <?php 
    }
    if ($previous_page_number) { ?>
        <li class="page-item"><a class="page-link" href="/users_management.php?upage=<?php echo $previous_page_number?>"><?php echo $previous_page_number?></a></li>
    <?php
    }
    ?>
    <li class="page-item"><a class="page-link active"><?php echo $current_page_number ?></a></li>

    <?php 
    if ($next_page_number) { ?>
        <li class="page-item"><a class="page-link" href="/users_management.php?upage=<?php echo $next_page_number?>"><?php echo $next_page_number ?></a></li>
    <?php
    }

    if ($has_first) {?>
        <li class="page-item">
            <a class="page-link" href="/users_management.php?upage=<?php echo $number_of_pages?>">Last</a>
        </li>
    <?php 
    }?>
  </ul>
</nav>