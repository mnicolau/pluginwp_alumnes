<?php
require_once (BC_DIR . 'includes/bc-alumnes-table.php');


showDataTable();

?>



<?php
/**
 * Mètode per crear, configura i mostrar la taula amb les dades dels alumnes
 */
function showDataTable()
{
    $taula = new AlumnesTable();
    $taula->init();

    $message = '';
    if ($taula->processar_delete()) {
        $message = '<div class="updated below-h2" id="message"><p>S\'ha esborrat 1 alumne</p></div>';
    }

    ?>
    <div class="wrap">
        <h1>Gestió d'alumnes <a class="add-new-h2"
                href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=alumnes_form'); ?>"><?php _e('Afegir', 'alumnes') ?></a>
        </h1>

        <?php echo $message; ?>
        <form method="post">
            <input type="hidden" name="page" value="llista_hidden" />
            <?php $taula->search_box('Cercar', 'search_id'); ?>
            <?php $taula->display(); ?>
        </form>
    </div>

    <?php
}


function alumnes_alumnes_form_page_handler()
{
    global $wpdb;
    $table_name = 'bc_alumnes';
    $message = '';
    $notice = '';
    echo "page_handler";
    // this is default $item which will be used for new records
    $default = array(
        'id' => 0,
        'dni' => '',
        'nom' => '',
        'cognoms' => '',
        'estudis' => ''
    );

    // here we are verifying does this request is post back and have correct nonce
    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        // combine our default item with request params
        $item = shortcode_atts($default, $_REQUEST);
        // validate data, and if all ok save item to database
        // if id is zero insert otherwise update
        $item_valid = alumne_validate($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = __('Alumne guardat', 'alumnes');
                } else {
                    $notice = __('S\'ha produït un error en desar les dades.', 'alumnes');
                }
            } else {
                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                if ($result) {
                    $message = __('S\'han actualitat les dades de l\'alumne', 'alumnes');
                } else {
                    $notice = __('S\'ha produït un error en desar les dades', 'alumnes');
                }
            }
        } else {
            // if $item_valid not true it contains error message(s)
            $notice = $item_valid;
        }
    } else {
        // if this is not post back we load item to edit or give new one to create
        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = __('No s\'ha trobat l\'alumne.', 'alumnes');
            }
        }
    }

    // here we adding our custom meta box
    add_meta_box('alumnes_form_meta_box', 'Dades d\'alumne', 'alumnes_form_meta_box_handler', 'alumne', 'normal', 'default');

    ?>
    <div class="wrap">

        <?php if (!empty($notice)): ?>
            <div id="notice" class="error">
                <p><?php echo $notice ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($message)): ?>
            <div id="message" class="updated">
                <p><?php echo $message ?></p>
            </div>
        <?php endif; ?>

        <form id="form" method="POST">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__)) ?>" />
            <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
            <input type="hidden" name="id" value="<?php echo $item['id'] ?>" />

            <div class="metabox-holder" id="poststuff">
                <div id="post-body">
                    <div id="post-body-content">
                        <?php /* And here we call our custom meta box */ ?>
                        <?php do_meta_boxes('alumne', 'normal', $item); ?>
                        <input type="submit" value="<?php _e('Desar', 'alumnes') ?>" id="submit" class="button-primary"
                            name="submit">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
}

/**
 * This function renders our custom meta box
 * $item is row
 *
 * @param $item
 */
function alumnes_form_meta_box_handler($item)
{
    ?>

    <table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
        <tbody>
            <tr class="form-field">
                <th valign="top" scope="row">
                    <label for="dni"><?php _e('DNI', 'alumnes') ?></label>
                </th>
                <td>
                    <input id="dni" name="dni" type="text" style="width: 95%" value="<?php echo esc_attr($item['dni']) ?>"
                        size="50" class="code" placeholder="<?php _e('DNI', 'alumnes') ?>" required>
                </td>
            </tr>
            <tr class="form-field">
                <th valign="top" scope="row">
                    <label for="nom"><?php _e('Nom', 'alumnes') ?></label>
                </th>
                <td>
                    <input id="nom" name="nom" type="text" style="width: 95%" value="<?php echo esc_attr($item['nom']) ?>"
                        size="50" class="code" placeholder="<?php _e('Nom', 'alumnes') ?>" required>
                </td>
            </tr>
            <tr class="form-field">
                <th valign="top" scope="row">
                    <label for="cognoms"><?php _e('Cognoms', 'alumnes') ?></label>
                </th>
                <td>
                    <input id="cognoms" name="cognoms" type="text" style="width: 95%"
                        value="<?php echo esc_attr($item['cognoms']) ?>" size="50" class="code"
                        placeholder="<?php _e('Cognoms', 'alumnes') ?>" required>
                </td>
            </tr>
            <tr class="form-field">
                <th valign="top" scope="row">
                    <label for="estudis"><?php _e('Estudis', 'alumnes') ?></label>
                </th>
                <td>
                    <input id="estudis" name="estudis" type="text" style="width: 95%"
                        value="<?php echo esc_attr($item['estudis']) ?>" size="50" class="code"
                        placeholder="<?php _e('Estudis', 'alumnes') ?>" required>
                </td>
            </tr>

        </tbody>
    </table>
    <?php
}

/**
 * Simple function that validates data and retrieve bool on success
 * and error message(s) on error
 *
 * @param $item
 * @return bool|string
 */
function alumne_validate($item)
{
    $messages = array();

    if (empty($item['dni']))
        $messages[] = __('El DNI és obligatori', 'alumnes');
    if (empty($item['nom']))
        $messages[] = __('El nom és obligatori', 'alumnes');
    if (!empty($item['cognoms']))
        $messages[] = __('Els cognoms són obligatoris', 'alumnes');

    //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
    //if(!empty($item['age']) && !preg_match('/[0-9]+/', $item['age'])) $messages[] = __('Age must be number');
    //...

    if (empty($messages))
        return true;
    return implode('<br />', $messages);
}

