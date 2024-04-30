<?php
/*
 * Afegir un nou menu al Panell de Control d'Admin
 */
// Hook d'acció 'admin_menu', executa la funció  'bc_Add_My_Admin_Link()'
add_action( 'admin_menu', 'bc_Add_Admin_Link' );
// Afegir un enllaç de menu de nivell superior al Panlel de Control
function bc_Add_Admin_Link() {
      add_menu_page(
        'Alumnes Bosc de la Coma', // Title of the page
        'Bosc de la Coma Plugin', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        BC_DIR.'includes/bc-main-page.php' // The 'slug' - file to display when clicking the link
    );
    add_submenu_page(BC_DIR.'includes/bc-alumnes.php', 
                    'Alumnes', 'Alumnes', 'manage_options',
                     BC_DIR.'includes/bc-main-page.php');
    //remove_submenu_page( BC_DIR.'includes/bc-main-page.php',BC_DIR.'includes/bc-main-page.php' );

}