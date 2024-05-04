<?php

if (!class_exists('DataTable')) {
    require_once ('data-table.php');
}
class AlumnesTable extends DataTable {

    function init() {
        $this->set_table_name('bc_alumnes');
        $this->set_columns(
            array(
                'dni' => 'DNI',
                'nom' => 'Nom',
                'cognoms' => 'Cognoms',
                'estudis' => 'Estudis'
            )
        );
        $this->set_sortable_columns(
            array(
                'dni' => array('id', false),
                'nom' => array('nom', false),
                'cognoms' => array('cognoms', false),
                'estudis' => array('estudis', false)
            )
        );
        $this->set_column_name_links('dni');
        $this->default_sort_column('dni');
        $this->prepare_items();
    }

}