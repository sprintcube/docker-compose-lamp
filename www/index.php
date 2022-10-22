<?php

// getting the sites set in the virtual host files
$sites = lamp_docker__get_sites_from_vhost();

?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LAMP STACK</title>
        <link rel="stylesheet" href="/assets/css/bulma.min.css">
    </head>
    <body>
        <section class="hero is-medium is-info is-bold">
            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title">
                        LAMP
                    </h1>
                    <h2 class="subtitle">
                        Local Development Environment
                    </h2>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="columns">
                    <div class="column">
                        <h3 class="title is-3 has-text-centered">Environment</h3>
                        <hr>
                        <div class="content">
                            <ul>
                                <li><?= apache_get_version(); ?></li>
                                <li>PHP <?= phpversion(); ?></li>
                                <li>
                                    <?php
                                        $link = mysqli_connect("database", "root", $_ENV['MYSQL_ROOT_PASSWORD'], null);

                                        /* check connection */
                                        if (mysqli_connect_errno()) {
                                            printf("MySQL connecttion failed: %s", mysqli_connect_error());
                                        } else {
                                            /* print server version */
                                            printf("MySQL Server %s", mysqli_get_server_info($link));
                                        }
                                        /* close connection */
                                        mysqli_close($link);
                                    ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="column">
                        <h3 class="title is-3 has-text-centered">Quick Links</h3>
                        <hr>
                        <div class="content">
                            <ul>
                                <li><a href="/phpinfo.php">phpinfo()</a></li>
                                <li><a href="http://localhost:<? print $_ENV['PMA_PORT']; ?>">phpMyAdmin</a></li>
                                <li><a href="/test_db.php">Test DB Connection with mysqli</a></li>
                                <li><a href="/test_db_pdo.php">Test DB Connection with PDO</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="section">
            <div class="container">
                <div class="columns">
                    <div class="column">
                        <h3 class="title is-3 has-text-centered">Sites set as Virtual Hosts</h3>
                        <hr>
                        <div class="content">
                            <?php if (count($sites)): ?>
                                <ul>
                                    <?php foreach ($sites as $site): ?>
                                        <li>
                                            <a href="http://<?= $site ?>">
                                                <?= $site ?>
                                            </a>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                            <?php else: ?>
                                <h4>No virtual host has been set yet.</h4>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="columns">
                    <div class="column">
                        <h3 class="title is-3 has-text-centered">Document Root Folders and Files</h3>
                        <hr>
                        <div class="content">
                            <div class="is-flex is-flex-wrap-wrap">
                                <?php foreach(lamp_docker__get_document_root_items() as $item): ?>
                                    <div class="box p-2 m-2" style="width: 30%;">
                                        <a href="<?= $item['href'] ?>" class="is-flex is-align-content-center">
                                            <img src="assets/<?= $item['is_dir'] ? 'folder' : 'file' ?>.png" width="24px">
                                            <span class="pl-2"><?= $item['name'] ?></span>
                                        </a>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    </body>
</html>

<?php

/**
 * Get the list of server names set in the virtual hosts CONF files.
 *
 * @return array
 */
function lamp_docker__get_sites_from_vhost(): array
{
    $filesContent = '';
    $sites = [];
    
    if (!is_dir('/etc/apache2/sites-enabled/')) return [];

    // placing all the content of the .CONF files from the folder "/etc/apache2/sites-enabled/"
    // in the variable $filesContent
    foreach (scandir('/etc/apache2/sites-enabled/') as $fileName) {
        if (substr($fileName, -5) == '.conf') {
            $filesContent .= file_get_contents('/etc/apache2/sites-enabled/' . $fileName) . PHP_EOL;
        }
    }
    
    // searching for the ServerName
    foreach (explode(PHP_EOL, $filesContent) as $line) {
        $line = trim($line);
        if (substr($line, 0, 1) == '#') continue;

        $pieces = explode('ServerName', $line);
        if (count($pieces) > 1) {
            $sites[] = trim($pieces[1]);
        }
    }

    asort($sites);

    return $sites;
}

/**
 * Return the list of all folders and files of the WWW folder.
 *
 * @return array
 */
function lamp_docker__get_document_root_items(): array
{
    if (!isset($_SERVER['DOCUMENT_ROOT'])) return [];

    $items = scandir($_SERVER['DOCUMENT_ROOT']);
    sort($items);

    $folders = [];
    $files = [];

    foreach ($items as $item) {
        if ($item == '.' or $item == '..') continue;

        if (is_dir($item)) {
            $folders[] = [ 'name' => $item, 'href' => $item . '/', 'is_dir' => true ];
        } else {
            $files[] = [ 'name' => $item, 'href' => $item, 'is_dir' => false ];
        }
    }

    return array_merge($folders, $files);
}