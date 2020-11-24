<?php 
/*
Plugin Name: CodeInsert
Description: This let you insert HTML code sets within a post
Version: 1.0
Author: Yizan Chen
*/

// Hook the 'admin_menu' action hook, run the function named 'CI_Add_My_Admin_Link()'
add_action( 'admin_menu', 'CI_Add_My_Admin_Link' );
 
// Add a new top level menu link to the ACP
function CI_Add_My_Admin_Link()
{
      add_menu_page(
        'Setting Page', // Title of the page
        'Code Insert', // Text to show on the menu link
        'administrator', // only administrator see the link
        __FILE__, // The 'slug' - file to display when clicking the link
        'CI_setting_page'
    );
    add_action( 'admin_init', 'register_CI_settings' );

}

function register_CI_settings() {
	//register our settings
    register_setting( 'CI-settings-group', 'codeSet' );
    register_setting( 'CI-settings-group', 'insertCodition' );
    register_setting( 'CI-settings-group', 'paragraphLimit' );
}

//save setting
function CI_setting_page(){
?>
<div class="wrap">
<h1>Setting</h1>
<form method="post" action="options.php">
    <?php settings_fields( 'CI-settings-group' ); ?>
    <?php do_settings_sections( 'CI-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Code Set</th>
        <td><textarea rows="4" cols="50" name="codeSet"> <?php echo  get_option('codeSet') ; ?> </textarea></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Insert Codition</th>
        <td><input type="text" name="insertCodition" value="<?php echo get_option('insertCodition') ; ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Paragraph Limit</th>
        <td><input type="number" name="paragraphLimit" value="<?php echo  get_option('paragraphLimit') ; ?>" /></td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
<?php    
}


//start the insert function
add_filter( 'the_content', 'CI_insert');

$codeset = get_option('codeSet');
$insertCondition = get_option('insertCodition');

//function to start insert
function CI_insert( $content ){
    global $post, $codeset,$insertCondition;
    if( $post->post_type == 'post' ){
        return CI_afterInsert($codeset,$content,$insertCondition);
    }

    return $content;
}

//look at all the p tag in our post
function CI_afterInsert($codeset,$content,$insertCondition){
    $closing_p = '</p>';
    $paragraphs = explode( $closing_p, $content);
    foreach ($paragraphs as $index => $paragraph){
        if(trim($paragraph)){
            $paragraphs[$index] .= $closing_p;
        }
        if( $insertCondition ){
        $paragraphs[$index] .= $codeset;
        }
    }
    return implode( '',$paragraphs);
}



?>