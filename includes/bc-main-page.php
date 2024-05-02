<?php
require_once (BC_DIR . 'includes/bc-alumnes-table.php');
?>


<div class="wrap">
    <h1>Gestió d'alumnes</h1>
    <p>Llista d'alumnes</p>

    <?php showDataTable(obtenirAlumnes()); ?>
</div>


<?php

function showDataTable($rows)
{
    $myListTable = new BCTable();
    $myListTable->set_rows($rows);
    $myListTable->prepare_items();
    ?>
    <form method="post">
        <input type="hidden" name="page" value="my_list_test" />
        <?php $myListTable->search_box('Cercar', 'search_id'); ?>
    </form>
    <?php $myListTable->display();
}


function data_table($db_data)
{
    if (!is_array($db_data) || empty($db_data))
        return false;

    // Get the table header cells by formatting first row's keys
    $header_vals = array();
    $keys = array_keys($db_data[0]);
    foreach ($keys as $row_key) {
        $header_vals[] = ucwords(str_replace('_', ' ', $row_key)); // capitalise and convert underscores to spaces
    }
    $header = "<thead><tr><th>" . join('</th><th>', $header_vals) . "</th></tr></thead>";

    // Make the data rows
    $rows = array();
    foreach ($db_data as $row) {
        $row_vals = array();
        foreach ($row as $key => $value) {

            // format any date values properly with WP date format
            if (strpos($key, 'date') !== false || strpos($key, 'modified') !== false) {
                $date_format = get_option('date_format');
                $value = mysql2date($date_format, $value);
            }
            $row_vals[] = $value;
        }
        $rows[] = "<tr><td>" . join('</td><td>', $row_vals) . "</td></tr>";
    }

    // Put the table together and output
    echo '<table class="wp-list-table widefat fixed posts">' . $header . '<tbody>' . join($rows) . '</tbody></table>';

    return true;
}

function obtenirAlumnes()
{
    global $wpdb;
    $query = "SELECT bc_alumnes.id as id, bc_alumnes.dni as dni, bc_alumnes.nom, bc_alumnes.cognoms, bc_alumnes.estudis
        FROM bc_alumnes";
    $result = $wpdb->get_results($query, ARRAY_A);

    return $result;
}
