<?php
/*
Plugin Name: OPG Webs Links
Plugin URI: http://www.oscarperez.es/wordpress-plugins/opg_webslinks.zip
Description: This Web links plugin helps to manage the links of interest easily over the WordPress blog. This webLinks have three fields: idLink, name and url
Author: Oskar Pérez
Author URI: http://www.oscarperez.es/
Version: 1.0
License: GPLv2
*/
?>
<?php

    //registramos el fichero js que necesitamos
    wp_register_script('myWebLinkScript', WP_PLUGIN_URL .'/opg_weblinks/opg_weblinks.js', array('jquery','media-upload','thickbox'));
    wp_enqueue_script('myWebLinkScript');      

    /* Con este código, se crea una linea en el menú de Administración */
    function opg_show_menu_links(){
        add_menu_page('Oscar Pérez Plugins','Oscar Pérez Plugins','manage_options','opg_plugins','opg_plugin_links_show_form_in_wpadmin', '', 110);
        add_submenu_page( 'opg_plugins', 'Enlaces de interes', 'Enlaces de interes', 'manage_options', 'opg_weblinks', 'opg_plugin_links_show_form_in_wpadmin');
        remove_submenu_page( 'opg_plugins', 'opg_plugins' );             
    }
    add_action( 'admin_menu', 'opg_show_menu_links' );


    //Hook al activar y desactivar el plugin
    register_activation_hook( __FILE__, 'opg_plugin_links_activate' );
    register_uninstall_hook( __FILE__, 'opg_plugin_links_uninstall' );


    // Se crea la tabla al activar el plugin
    function opg_plugin_links_activate() {
        global $wpdb;

        $sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'opg_plugin_links` 
            ( `idLink` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY , 
              `name` VARCHAR( 100 ) NOT NULL , 
              `url` VARCHAR( 140 ) NOT NULL )';
        $wpdb->query($sql);
    }

    // Se borra la tabla al desactivar el plugin
    function opg_plugin_links_uninstall() {
        global $wpdb;
        $sql = 'DROP TABLE `' . $wpdb->prefix . 'opg_plugin_links`';
        $wpdb->query($sql);
    }





    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        F U N C I O N E S   D E   A C C E S O   A   B A S E   D E   D A T O S
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    //función que guarda en base de datos la información introducida en el formulario
    function opg_link_save($name, $url)
    {
        global $wpdb;
        if (!( isset($name) && isset($url) )) {
            _e('cannot get \$_POST[]');
            exit;
        }

        $name = trim($name);
        $url  = trim($url);


        //comprobamos si empieza por http
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }

        $save_or_no = $wpdb->insert($wpdb->prefix . 'opg_plugin_links', array(
                'idLink' => NULL, 'name' => esc_js($name), 'url' => $url,
            ),
            array('%d', '%s', '%s' )
        );
        if (!$save_or_no) {
            _e('<div class="updated"><p><strong>Error. Please install plugin again</strong></p></div>');
            return false;
        }
        else{
            _e('<div class="updated"><p><strong>Información del enlace guardada correctamente</strong></p></div>');            
        }
        return true;
    }


    //función que borra un teléfono de la base de datos
    function opg_link_remove($id)
    {
        global $wpdb;
        if ( !isset($id) ) {
            _e('cannot get \$_GET[]');
            exit;
        }

        $delete_or_no = $wpdb->delete($wpdb->prefix . 'opg_plugin_links', array('idLink' => $id), array( '%d' ) );
        if (!$delete_or_no) {
            _e('<div class="updated"><p><strong>Error. Please install plugin again</strong></p></div>');
            return false;
        }
        else{
            _e('<div class="updated"><p><strong>Se ha borrado la información del enlace</strong></p></div>');            
        }
        return true;
    }

    //función para actualizar un teléfono
    function opg_link_update($id, $name, $url)
    {
        global $wpdb;
        if (!( isset($name) && isset($url) )) {
            _e('cannot get \$_POST[]');
            exit;
        }

        $update_or_no = $wpdb->update($wpdb->prefix . 'opg_plugin_links', 
            array('name' => esc_js(trim ($name)), 'url' => trim ($url)),
            array('idLink' => $id),
            array('%s', '%s')
        );
        if (!$update_or_no) {
            _e('<div class="updated"><p><strong>Error. Please install plugin again</strong></p></div>');
            return false;
        }
        else{
            _e('<div class="updated"><p><strong>Enlace modificado correctamente</strong></p></div>');            
        }
        return true;
    }


    //función que recupera un telefono usando el ID
    function opg_plugin_link_getId($id)
    {
        global $wpdb;
        $row1 = $wpdb->get_row("SELECT name, url  FROM " . $wpdb->prefix . "opg_plugin_links  WHERE idLink=".$id);
        return $row1;
    }


    //función que recupera los teléfonos guardados de la base de datos
    function opg_plugin_links_getData()
    {
        global $wpdb;

        $links = $wpdb->get_results( 'SELECT idLink, name, url FROM ' . $wpdb->prefix . 'opg_plugin_links
         ORDER BY name' );
        if (count($links)>0){            
?>
            <hr style="width:94%; margin:20px 0">   
            <h2>Listado de enlaces</h2>
            <table class="wp-list-table widefat manage-column" style="width:98%">            
             <thead>
                <tr>
                    <th scope="col" class="manage-column"><span>Nombre</span></th>
                    <th scope="col" class="manage-column"><span>Url</span></th>
                    <th scope="col" class="manage-column"><span>&nbsp;</span></th>
                    <th scope="col" class="manage-column"><span>&nbsp;</span></th>                    
                </tr>
             </thead>
             <tbody>

<?php
            $cont = 0;
            foreach ( $links as $link ) {
                $cont++;
                if ($cont%2 ==1){ echo '<tr class="alternate">'; }
                else{ echo '<tr>'; }

?>
                    <td><?php echo( $link->name ); ?></td>
                    <td><?php echo( $link->url ); ?></td>
                    <td><a href="admin.php?page=opg_weblinks&amp;task=edit_link&amp;id=<?php echo( $link->idLink ); ?>"><img src="<?php echo WP_PLUGIN_URL.'/opg_concejales/img/modificar.png'?>" alt="Modificar"></a></td>
                    <td><a href="#"><img src="<?php echo WP_PLUGIN_URL.'/opg_aside/img/papelera.png'?>" alt="Borrar" id="<?php echo( $link->idLink ); ?>" class="btnDeleteLink"></a></td>
                </tr>
<?php                
            }
        }

?>
                </tbody>
            </table>
<?php
        return true;
    }



    /*
       F U N C I O N   Q U E   S E   E J E C U T A   A L   A C C E D E R   A L   P L U G I N   D E S D E   A D M I N I S T R A C I O N
       La función la definimos en la llamada add_menu_page()
    */
    function opg_plugin_links_show_form_in_wpadmin(){
 
        $valueInputUrl = "";
        $valueInputName  = "";
        $valueInputId    = "";

        if(isset($_POST['action']) && $_POST['action'] == 'salvaropciones'){

            //si el input idLink (hidden) está vacio, se trata de un nuevo registro
            if( strlen($_POST['idLink']) == 0 ){
                //guardamos el teléfono
                opg_link_save($_POST['name'], $_POST['url']);
            }
            else{
                opg_link_update($_POST['idLink'], $_POST['name'], $_POST['url']);
            }   
        }
        else{
            //recuperamos la tarea a realizar (edit o delete)
            if (isset($_GET["task"]))
                $task = $_GET["task"]; //get task for choosing function
            else
                $task = '';
            //recuperamos el id del telefono
            if (isset($_GET["id"]))
                $id = $_GET["id"];
            else
                $id = 0;


            switch ($task) {
                case 'edit_link':
                    echo("<div class='wrap'><h2>Modificar información de la url</h2></div>"); 
                    
                    $row = opg_plugin_link_getId($id);
                    $valueInputUrl = $row->url;
                    $valueInputName  = $row->name;
                    $valueInputId    = $id;
                    break;
                case 'remove_link':
                    opg_link_remove($id);
                    break;
                default:
                    echo("<div class='wrap'><h2>Añadir una nueva url</h2></div>"); 
                    break;
            }
        }
?>
        <form method='post' action='admin.php?page=opg_weblinks' name='opgPluginAdminForm' id='opgPluginAdminForm'>
            <input type='hidden' name='action' value='salvaropciones'> 
            <table class='form-table'>
                <tbody>
                    <tr>
                        <th><label for='name'>Nombre</label></th>
                        <td>
                            <input type='text' name='name' id='name' placeholder='Introduzca el nombre' value="<?php echo $valueInputName ?>" style='width: 500px'>
                        </td>
                    </tr>
                    <tr>
                        <th><label for='url'>Url</label></th>
                        <td>
                            <input type='text' name='url' id='url' placeholder='Introduzca la url' value="<?php echo $valueInputUrl ?>" style='width: 500px'>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' style='padding-left:140px'>
                            <input type='submit' value='Enviar'>
                            <input type='hidden' name="idLink" value="<?php echo $valueInputId ?>">
                        </td>
                    </tr>
                </tbody>
            </table>        
        </form>

<?php
        //se muestra el listado de todos los teléfonos guardados
        opg_plugin_links_getData();
?>        
<?php }?>