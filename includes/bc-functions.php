<?php

/**
 * Funció amb els imports addicionals que volem que tingui el nostre plugin
 */
function libs_import()
{
  //wp_enqueue_style('datatable-style', 'https://cdn.jsdelivr.net/npm/datatables@1.10.18/media/css/jquery.dataTables.min.css', '1.10.18', true);
  wp_enqueue_style('datatable-style', ' https://cdn.datatables.net/2.0.6/css/dataTables.dataTables.css', '5.3.0', true);
  wp_enqueue_style('datatable-style-2', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css', '5.3.0', true);
  wp_enqueue_style('datatable-style-3', 'https://cdn.datatables.net/2.0.6/css/dataTables.bootstrap5.css', '2.0.6', true);
  
  //wp_enqueue_script('datatable-script', 'https://cdn.jsdelivr.net/npm/datatables@1.10.18/media/js/jquery.dataTables.min.js', array('jquery'));
  wp_enqueue_script('datatable-script-2', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js', array('jquery'));
  wp_enqueue_script('datatable-script-3', 'https://cdn.datatables.net/2.0.6/js/dataTables.js', array('jquery'));
  wp_enqueue_script('datatable-script-4', 'https://cdn.datatables.net/2.0.6/js/dataTables.bootstrap5.js', array('jquery'));

  wp_register_script('bc-alumnes-script', plugins_url('bc-alumnes/js/alumnes.js'), array(), '1.0', true);

  wp_localize_script('bc-alumnes-script', 'ajaxobj', array('url' => admin_url('admin-ajax.php')));
  wp_enqueue_script('bc-alumnes-script');
}

add_action('wp_ajax_obtenir_dades', 'ajax_answering');
add_action('wp_ajax_nopriv_obtenir_dades', 'ajax_answering');
add_action('wp_enqueue_scripts', 'libs_import');

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
      $output = 'No function specified, check your jQuery.ajax() call';
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
 * d'un shortcod [alumnes_table]
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


/*
 * Afegir un nou menu al Panell de Control d'Admin
 */

// Hook d'acció 'admin_menu', executa la funció  'bc_Add_My_Admin_Link()'
add_action('admin_menu', 'bc_Add_Admin_Link');

// Afegir un enllaç de menu de nivell superior al Panlel de Control
function bc_Add_Admin_Link()
{
  global $bc_alumnes_page;

  $bc_alumnes_page = add_menu_page(
    'Alumnes Bosc de la Coma', // Title of the page
    'Bosc de la Coma Plugin', // Text to show on the menu link
    'manage_options', // Capability requirement to see the link
    BC_DIR . 'includes/bc-main-page.php' // The 'slug' - file to display when clicking the link
  );
  add_action("load-$bc_alumnes_page", "bc_alumnes_screen_options");

  /*add_submenu_page(BC_DIR.'includes/bc-alumnes.php', 
                  'Alumnes', 'Alumnes', 'manage_options',
                   BC_DIR.'includes/bc-main-page.php');*/
  //remove_submenu_page( BC_DIR.'includes/bc-main-page.php',BC_DIR.'includes/bc-main-page.php' );

}

function bc_alumnes_screen_options()
{
  global $bc_alumnes_page;
  global $table;

  $screen = get_current_screen();

  // get out of here if we are not on our settings page
  if (!is_object($screen) || $screen->id != $bc_alumnes_page)
    return;

  $args = array(
    'label' => __('Files per pàgina', 'bc-alumnes-admin-table'),
    'default' => 10,
    'option' => 'elements_per_page'
  );
  add_screen_option('per_page', $args);
  echo "hola!";
  $table = new AlumnesTable();
}