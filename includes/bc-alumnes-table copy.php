<?php
require_once (BC_DIR . 'includes/bc-list-table.php');

if (!class_exists('BC_List_Table')) {
    require_once (BC_DIR . 'includes/bc-list-table.php');
}
class BCTable extends BC_List_Table
{
    var $rows;

    function set_rows($rows)
    {
        $this->rows = $rows;
    }

    function get_columns()
    {
        $columns = array(
            'dni' => 'DNI',
            'nom' => 'Nom',
            'cognoms' => 'Cognoms',
            'estudis' => 'Estudis'
        );

        return $columns;
    }

    function prepare_items()
    {
        global $wpdb;
        $table_name = 'bc_alumnes'; 

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        usort($this->rows, array(&$this, 'usort_reorder'));

        $per_page = 10;
        $current_page = $this->get_pagenum();
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        // Sólo necesario porque usamos nuestros datos de ejemplo
        //$found_data = array_slice($dades, (($current_page - 1) * $per_page), $per_page);
        //$this->found_data = $this->rows;



        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * 
                                                          FROM $table_name 
                                                          ORDER BY $orderby $order 
                                                          LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);


        $this->set_pagination_args(
            array(
                'total_items' => $total_items,
                'per_page' => $per_page,
                'total_pages' => ceil($total_items / $per_page)
            )
        );

    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'dni':
            case 'nom':
            case 'cognoms':
            case 'estudis':
                return $item[$column_name];
            default:
                return print_r($item, true); // Mostramos todo el arreglo para resolver problemas
        }
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'dni' => array('id', false),
            'nom' => array('nom', false),
            'cognoms' => array('cognoms', false),
            'estudis' => array('estudis', false)
        );
        return $sortable_columns;
    }

    function usort_reorder($a, $b)
    {
        // Si no s'indica columna, por defecto els cognoms
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'cognoms';
        // Si no hi ha ordre, por defecto asendente
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
        // Indica com s'ordena
        $result = strcmp($a[$orderby], $b[$orderby]);
        // Envía l'adreça d'ordenació al final de usort
        return ($order === 'asc') ? $result : -$result;
    }


    function column_dni($item)
    {
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&action=%s&alumne=%s">Editar</a>', $_REQUEST['page'], 'edit', $item['id']),
            'delete' => sprintf('<a href="?page=%s&action=%s&alumne=%s">Esborrar</a>', $_REQUEST['page'], 'delete', $item['id']),
        );

        return sprintf('%1$s %2$s', $item['dni'], $this->row_actions($actions));
    }
}

