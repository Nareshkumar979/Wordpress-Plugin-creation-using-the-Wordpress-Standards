<?php

/*

Plugin Name: Destination Category

Plugin URI: http://sdkattech.com

Description: This plugin is used for the creation of the Custom Category for the Wordpress Destination Post type and it allows you to link the Category and the Sub Category using this plugin.

Version: 1.0

Author: Naresh Kumar.P

Author URI: http://sdkattech.com

License: NKPDC007

*/

//Creation of custom post type(Start)

add_action( 'init', 'create_destination_category' );

function create_destination_category() 

{

  $args = array(

            'labels' => array(

                'name' => 'Destination Categories',

                'singular_name' => 'Destination Category',

                'add_new' => 'Add New',

                'add_new_item' => 'Add New Destination Category',

                'edit' => 'Edit',

                'edit_item' => 'Edit Destination Category',

                'new_item' => 'New Destination Category',

                'view' => 'View',

                'view_item' => 'View Destination Category',

                'search_items' => 'Search Destination Category',

                'not_found' => 'No Destination Categories found',

                'not_found_in_trash' => 'No Destination Category found in Trash',            

            ),           

            'menu_position' => 15,

            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),            

            'menu_icon' => plugins_url( 'images/logo.png', __FILE__ ),

			'public' => true          

         );

		 register_post_type( 'destination_category',$args);		 	

 }//Creation of custom post type(End) 	

add_action( 'init', 'create_destination_subcategory' );

function create_destination_subcategory() 

{

$argsone = array(

'labels' => array(

'all_items' => 'Sub Categories',

'menu_name' => 'Sub Categories',

'singular_name' => 'Sub Category',

'edit_item' => 'Edit Sub Category',

'new_item' => 'New Sub Category',

'view_item' => 'View Sub Category',

'search_items' => 'Search Destination Sub Category',

'not_found' => 'No Destination Sub Category found.',

'not_found_in_trash' => 'No Destination Sub Category found in trash.'

),

'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),

'show_in_menu' => 'edit.php?post_type=destination_category',

'public' => true

);

register_post_type( 'destination_subcat', $argsone );

}


//Creation of Meta Box for post type "destination_category" (Start)

add_action( 'admin_init', 'my_destination_category' );

//destination_sub_category_admin -  is the required HTML id attribute

//Select Destination Sub Category -  is the text visible in the heading of the meta box section

//display_destination_subcategory_meta_box - is the callback which renders the contents of the meta box

//destination_category - is the name of the custom post type where the meta box will be displayed

// normal - defines the part of the page where the edit screen section should be shown

// high - defines the priority within the context where the boxes should show

function my_destination_category() {

    add_meta_box( 'destination_sub_category_admin','Select Destination Category','display_destination_subcategory_meta_box', 'destination_subcat', 'normal', 'high');

	function display_destination_subcategory_meta_box( $select_category ) {

    // Retrieve Current Selected Category ID based on the Category Created   

	global $wpdb;

	$selectcat="SELECT * FROM ".$wpdb->prefix."posts WHERE `post_type`='destination_category' AND `post_status`='publish' ORDER BY `ID` DESC";

	$resultant = $wpdb->get_results($selectcat);

	$rescount=count($resultant);

    $category_selected_id = intval( get_post_meta( $select_category->ID, 'destination_category_id', true ) );

    ?>

    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('css/metabox.css',__FILE__ ) ?>" />

    <table>

        <tr>

            <td style="width: 150px">Select Category</td>

            <td>

                <select style="width: 100px" name="category_selection" id="meta_box_category" style="float:left; width:50%; !important">

				<option value="cs0">Please Select</option>

                <?php				

                if($rescount==0)

				{?>

                <option value="null">No Category have been created</option>

				<?php

				}

				else

				{                

                // Generate all items of drop-down list

                foreach($resultant as $singleresultant)

				{

                ?>

                    <option value="<?php echo $singleresultant->ID; ?>" <?php echo selected( $singleresultant->ID, $category_selected_id ); ?>>

                    <?php echo $singleresultant->post_title; ?>

                    </option>

                    <?php

					}

					}

					?>

                </select>

            </td>

        </tr>

    </table>

    <?php

}

// Registering a Save Post Function

add_action( 'save_post', 'destination_admin_sub_category', 10, 2 );

function destination_admin_sub_category( $select_category_id, $select_category ) {

    // Check post type for movie reviews

    if ( $select_category->post_type == 'destination_subcat' ) {

        // Store data in post meta table if present in post data

		   if ( isset( $_POST['category_selection'] ) && $_POST['category_selection'] != '' ) {

            echo update_post_meta( $select_category_id, 'destination_category_id', $_POST['category_selection'] );

        }

    }

}

}

add_filter( 'template_include', 'include_destination_category', 1 );

function include_destination_category( $template_path ) {

    if ( get_post_type() == 'destination_category' ) {

        if ( is_single() ) {

            // checks if the file exists in the theme first,

            // otherwise serve the file from the plugin

            if ( $theme_file = locate_template( array ( 'single-destination_category.php' ) ) ) {

                $template_path = $theme_file;

            } else {

                $template_path = plugin_dir_path( __FILE__ ) . '/single-destination_category.php';

            }

        }

    }

    return $template_path;

}
function categor_destination( ){
	global $wpdb;
	$dest_select = "SELECT * FROM ".$wpdb->prefix."posts WHERE `post_type`='destination_category' ";
	$opres = $wpdb->get_results($dest_select);?>
	<table>
	<?php foreach($opres as $des_results){
		$date=$des_results->post_date;?>
		 <tr>
         <td>
		<?php echo $des_results->post_title;?>
        </td>
        <td>
        <?php echo date(F);?>
        </td>
		</tr>
        
		<?php }
		?></table>
        <?php
	
}
add_shortcode( 'catdest', 'categor_destination' );
?>
