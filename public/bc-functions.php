<?php

/**
 * Funció amb els imports addicionals que volem que tingui el nostre plugin
 */
function libs_import()
{
  wp_enqueue_style('datatable-style', ' https://cdn.datatables.net/2.0.6/css/dataTables.dataTables.css', '5.3.0', true);
  wp_enqueue_style('datatable-style-2', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css', '5.3.0', true);
  wp_enqueue_style('datatable-style-3', 'https://cdn.datatables.net/2.0.6/css/dataTables.bootstrap5.css', '2.0.6', true);
  
  wp_enqueue_script('datatable-script-2', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js', array('jquery'));
  wp_enqueue_script('datatable-script-3', 'https://cdn.datatables.net/2.0.6/js/dataTables.js', array('jquery'));
  wp_enqueue_script('datatable-script-4', 'https://cdn.datatables.net/2.0.6/js/dataTables.bootstrap5.js', array('jquery'));

  wp_register_script('bc-alumnes-script', plugins_url('bc-alumnes/js/alumnes.js'), array(), '1.0', true);

  wp_localize_script('bc-alumnes-script', 'ajaxobj', array('url' => admin_url('admin-ajax.php')));
  wp_enqueue_script('bc-alumnes-script');
}
add_action('wp_enqueue_scripts', 'libs_import');

add_action('wp_ajax_obtenir_dades', 'ajax_answering');
add_action('wp_ajax_nopriv_obtenir_dades', 'ajax_answering');

/**
 * Funció que s'executa en resposta a la crida ajax de petició de dades del DataTable del frontend
 */
function ajax_answering()
{
  switch ($_POST['fn']) {
    case 'obtenir_dades_callback':
      obtenir_dades_callback();
      break;

    default:
      $output = 'Comprova la crida jQuery.ajax()';
      break;
  }
}
/**
 * Fer la consulta a la base de dades
 */
function obtenir_dades_callback()
{
  global $wpdb;
  $dades = $wpdb->get_results("SELECT * FROM bc_alumnes", ARRAY_A);
  echo json_encode($dades);
  wp_die();
}


/**
 * Afegir un [shortcode] per incloure el contingut del plugin dins una pàgina del frontend
 */
add_shortcode('alumnes', 'alumnes_table');

/** 
 * Funció que mostra una taula d'alumnes quan es demana el seu render a través
 * d'un shortcode [alumnes]
 */
function alumnes_table()
{
  ob_start(); ?>
  <table id="llistaAlumnes" class="table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>DNI</th>
        <th>Nom</th>
        <th>Cognoms</th>
        <th>Estudis</th>
      </tr>
    </thead>
    <tbody>
      <!-- Aquí es generaran les files de la taula amb JavaScript -->
    </tbody>
  </table>
  <?php
  return ob_get_clean();
}