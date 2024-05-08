<?php

/*
 * Afegir un nou menu al Panell de Control d'Admin
 */

// Hook d'acció 'admin_menu', executa la funció  'bc_Add_My_Admin_Link()'
add_action('admin_menu', 'bc_Add_Admin_Link');

// Afegir un enllaç de menu de nivell superior al Panlel de Control
function bc_Add_Admin_Link()
{
  //global $bc_alumnes_page;

  $bc_alumnes_page = add_menu_page(
    'Alumnes Bosc de la Coma', // Title of the page
    'Alumnes BC', // Text to show on the menu link
    'manage_options', // Capability requirement to see the link
    BC_DIR . 'admin/bc-main-page.php' // The 'slug' - file to display when clicking the link
    //BC_DIR . 'includes/bc-mpage.php' // The 'slug' - file to display when clicking the link
  );
 //7 add_action("load-$bc_alumnes_page", "bc_alumnes_screen_options");

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
  $table = new AlumnesTable();
}