<?php

global $wpdb;

$result = $wpdb->get_results("SELECT * FROM bc_alumnes", ARRAY_A );
foreach ( $result as $row ) {
    printf("%d %s %s %s %s<br>\n", $row["id"], $row["dni"], $row["nom"], $row["cognoms"], $row["estudis"]);
}
