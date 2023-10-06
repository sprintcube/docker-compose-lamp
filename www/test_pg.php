<?php
$link = pg_connect('host=postgres port=5432 dbname=' . $_ENV['POSTGRES_DB'] . ' user=' . $_ENV['POSTGRES_USER'] . ' password=' . $_ENV['POSTGRES_PASSWORD']);

if (!$link) {
    printf("PostgreSQL connection failed: %s", pg_last_error($link));
    echo "Error: Unable to connect to PostgreSQL." . PHP_EOL;
    echo "Debugging error: " . pg_last_error($link) . PHP_EOL;
    exit;
}

echo "Success: A proper connection to PostgreSQL was made! The docker database is great." . PHP_EOL;

pg_close($link);
