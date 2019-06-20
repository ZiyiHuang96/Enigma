<?php
/**
 * Function to localize our site
 * @param $site The Site object
 */
return function(Enigma\Site $site) {
    // Set the time zone
    date_default_timezone_set('America/Detroit');
    $site->setEmail('huangzi7@cse.msu.edu');
    $site->setRoot('/~huangzi7/project2');
    $site->dbConfigure('mysql:host=mysql-user.cse.msu.edu;dbname=huangzi7',
        'huangzi7',       // Database user
        'Huangziyi0',     // Database password
        'enigma_');            // Table prefix
};