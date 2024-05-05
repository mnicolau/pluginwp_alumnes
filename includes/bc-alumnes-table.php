<?php

if (!class_exists('DataTable')) {
    require_once ('data-table.php');
}
/**
 * Hereta de DataTable i afegeix un mètode per inicialitzar els paràmetres inicials. 
 */
class AlumnesTable extends DataTable
{

    function init()
    {
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
        $this->set_edit_form('alumnes_form');
        $this->processar_delete();
        $this->prepare_items();
    }

    /**
     * Processa si s'ha produït una acció d'esborra una fila
     */
    function processar_delete()
    {
        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['bc_alumnes']) ? $_REQUEST['bc_alumnes'] : array();
            if (is_array($ids))
                $ids = implode(',', $ids);
            else
                $ids = $_REQUEST['bc_alumnes'];

            return $this->delete_data($ids);
        }
        return false;
    }

}