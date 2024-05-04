<?php
// Loading WP_List_Table class file
// We need to load it as it's not automatically loaded by WordPress
if (!class_exists('WP_List_Table')) {
    require_once (ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
class DataTable extends WP_List_Table
{
    var $table_data;
    var $table_name;
    var $columns;
    var $sortable_columns;
    var $default_sort_column;

    var $column_links;

    var $column_name_links;

    function set_table_name($name)
    {
        $this->table_name = $name;
    }

    function get_table_name()
    {
        return $this->table_name;
    }

    function set_columns($columns)
    {
        $this->columns = $columns;
    }

    function get_columns()
    {
        return $this->columns;
    }

    function set_column_links($value) {
        $this->column_links = $value;
    }

    function set_column_name_links($value){
        $this->column_name_links = $value;
    }

    function prepare_items()
    {
        global $wpdb;
        //$table_name = 'bc_alumnes';
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $per_page = $this->get_items_per_page('elements_per_page', 10);

        if (isset($_POST['s'])) {
            $this->table_data = $this->get_table_data($per_page, $paged, $_POST['s']);
        } else {
            $this->table_data = $this->get_table_data($per_page, $paged);
        }
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $primary = $this->default_sort_column;
        $this->_column_headers = array($columns, $hidden, $sortable, $primary);

        usort($this->table_data, array(&$this, 'usort_reorder'));

        $hidden = (
            is_array(
                get_user_meta(
                    get_current_user_id(),
                    'managetoplevel_page_supporthost_list_tablecolumnshidden',
                    true
                )
            )
        ) ?
            get_user_meta(get_current_user_id(), 'managetoplevel_page_supporthost_list_tablecolumnshidden', true) :
            array();

        $current_page = $this->get_pagenum();
        $total_items = count($this->table_data);

        //$found_data = array_slice($dades, (($current_page - 1) * $per_page), $per_page);
        //$this->found_data = $this->rows;

        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        $this->table_data = array_slice($this->table_data, (($current_page - 1) * $per_page), $per_page);

        $this->set_pagination_args(
            array(
                'total_items' => $total_items,
                'per_page' => $per_page,
                'total_pages' => ceil($total_items / $per_page)
            )
        );

        $this->items = $this->table_data;

    }

    function column_default($item, $column_name)
    {
        if (isset($column_name) && $column_name == $this->column_name_links) {
            $actions = array(
                'edit' => sprintf('<a href="?page=%s&action=%s&%s=%s">Editar</a>', $_REQUEST['page'], 'edit', $this->table_name, $item['id']),
                'delete' => sprintf('<a href="?page=%s&action=%s&%s=%s">Esborrar</a>', $_REQUEST['page'], 'delete', $this->table_name, $item['id']),
            );
        
            return sprintf('%1$s %2$s', $item[$column_name], $this->row_actions($actions));
        } else {
            return $item[$column_name];
        } 
    }

    function get_sortable_columns()
    {
        return $this->sortable_columns;
    }

    function set_sortable_columns($sortable_columns)
    {
        $this->sortable_columns = $sortable_columns;
    }

    function set_default_sort_column($column_name)
    {
        $this->default_sort_column = $column_name;
    }

    function get_default_sort_column()
    {
        return !empty($this->default_sort_column) ? $this->default_sort_column : "id";
    }

    function usort_reorder($a, $b)
    {
        // Si no s'indica columna, per defecte els cognoms
        //$orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'cognoms';
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : $this->get_default_sort_column();
        // Si no hi ha ordre, per defecte asendent
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
        // Indica com s'ordena
        $result = strcmp($a[$orderby], $b[$orderby]);
        // Enviaa l'adreça d'ordenació al final de usort
        return ($order === 'asc') ? $result : -$result;
    }

    
    // Get table data
    private function get_table_data($per_page, $paged, $search = '')
    {
        global $wpdb;

        if (!empty($search)) {
            return
                $wpdb->get_results(
                    "SELECT * from $this->table_name",
                    ARRAY_A
                );
            /*$wpdb->get_results(
                "SELECT * from $this->table_name WHERE name Like '%{$search}%' OR description Like '%{$search}%' OR status Like '%{$search}%'",
                ARRAY_A
            );*/
        } else {


            return $wpdb->get_results($wpdb->prepare("SELECT * 
                                                        FROM {$this->table_name} 
                                                        LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);
        }
    }

    // Plugin menu callback function
    function supporthost_list_init()
    {
        // Creating an instance
        $table = new Supporthost_List_Table();

        echo '<div class="wrap"><h2>SupportHost Admin Table</h2>';
        echo '<form method="post">';
        // Prepare table
        $table->prepare_items();
        // Display table
        $table->display();
        echo '</div></form>';
    }
}

