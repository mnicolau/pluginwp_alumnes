<?php
require_once ('bc-alumnes-table.php');
?>


<div class="wrap">
    <h1>Gestió d'alumnes</h1>
    <?php showDataTable();?>
</div>

<?php

function showDataTable() {
        $taula = new AlumnesTable();
        $taula->init();
        $taula->set_table_name('bc_alumnes');
        $taula->set_columns(
            array(
                'dni' => 'DNI',
                'nom' => 'Nom',
                'cognoms' => 'Cognoms',
                'estudis' => 'Estudis'
            )
        );
        $taula->set_sortable_columns(
            array(
                'dni' => array('id', false),
                'nom' => array('nom', false),
                'cognoms' => array('cognoms', false),
                'estudis' => array('estudis', false)
            )
        );
        $taula->set_column_name_links('dni');
        $taula->default_sort_column('dni');
        $taula->prepare_items();
        ?>
        <form method="post">
            <input type="hidden" name="page" value="my_list_test" />
            <?php $taula->search_box('Cercar', 'search_id'); ?>
            <?php $taula->display(); ?>
        </form>
    <?php
}  