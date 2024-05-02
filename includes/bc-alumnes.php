
<?php
database_table_example_page();

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

/* Example usage with a menu page */
function database_table_example_page()
{

    // example query: ALUMNES DEL CENTRE
    global $wpdb;
    $query = "SELECT alumnes.dni as dni, alumnes.nom, alumnes.cognoms, alumnes.telefon
        FROM alumnes";
    $result = $wpdb->get_results($query, ARRAY_A);

    ?>
        <div class="wrap">
            <h1>Hola!</h1>
            <p>Aquesta és la primera pàgina del plugin</p>

            <?php data_table($result); ?>
        </div>
        
    <?php
}


?>