<?php
/**
 * Create settings page for team manager post type
 *
 *
 * @author: Maidul
 * @version: 1.0.0
 */
class TeamManagerSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "team manger"
        add_submenu_page(
            'edit.php?post_type=team_manager',
            'Settings Team Manger', 
            'Team Settings', 
            'manage_options', 
            'team_manager', 
            array( $this, 'create_admin_page' )
        );
        add_submenu_page(
            'edit.php?post_type=team_manager',
            'Shortcode Generator', 
            'Shortcode Generator', 
            'manage_options', 
            'team-manager-shortcode-generator', 
            array( $this, 'create_admin_shortcode_generator' )
        );        
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {

        ?>
        <div class="wrap">
            <h2><?php _e('Team Manager settings', 'wp-team-manager'); ?></h2> 
            <?php settings_errors(); ?>      
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'tm-settings-group' );   
                do_settings_sections( 'tm-settings-group' );
            ?>

        <?php 
        
        $settings = get_option('wtm_settings');

        $tm_social_type = (isset($settings['social_type'])) ? $settings['social_type'] : null;
		    $tm_social_size = (isset($settings['social_image_size'])) ? $settings['social_image_size'] : null;
		    $tm_primary_color = (isset($settings['primary_color'])) ? $settings['primary_color'] : 0;
		    $link_new_window = (isset($settings['link_new_window'])) ? $settings['link_new_window'] : 0;
		    $single_view = (isset($settings['single_view'])) ? $settings['single_view'] : null;
        $tm_custom_template = (isset($settings['custom_template'])) ? $settings['custom_template'] : null;
        $tm_custom_css = (isset($settings['custom_css'])) ? $settings['custom_css'] : '';
        
		    if (empty($tm_custom_template)) {
          //set default
		    $tm_custom_template='
		    <div class="%layout%">
		    <div class="team-member-info">
		    %image%
		     %sociallinks%
		    </div><div class="team-member-des">
		    <h2 class="team-title">%title%</h2>
		    <h4 class="team-position">%jobtitle%</h4>
		    %content%
		    %otherinfo%
		    </div>
		    </div>';
		    }
		     ?>
		    <table class="form-table">   
            <tr valign="top">
		        <th scope="row"><label><?php _e('Social icon font size','wp-team-manager'); ?></label></th>
		        <td>
            <input type="number" name="wtm_settings[social_font_size]" class="small-text" value="<?php echo $settings['social_font_size'] ?>">
            <p class="description">Update font size(PX) of the Font Awesome font</p>
		        </td>
		        </tr>               
            
		        <tr valign="top">
		        <th scope="row"><label><?php _e('Disable single team member view','wp-team-manager'); ?></label></th>
		        <td>
		        <input type="checkbox" name="wtm_settings[single_view]" value="1" <?php checked( $single_view, 1); ?>/> Yes
		        </td>
		        </tr> 

            <tr valign="top">
                <th scope="row"><label for="tm_custom_template"><?php _e('HTML Template','wp-team-manager');?></label></th>
                <td class="">
                <textarea name="wtm_settings[custom_template]" id="tm_custom_template" class="wp-editor-area" rows="10" cols="80"><?php echo $tm_custom_template; ?></textarea>
                <p class="description"><?php _e('Edit the HTML template if you want to customize it.', 'wp-team-manager'); ?></p>
		            <p class="description"><?php _e('Here is the list of available tags.', 'wp-team-manager'); ?></p>
		            <p class="description"><?php _e('<code>%title%</code> , <code>%content%</code> , <code>%image%</code>, <code>%sociallinks%</code>, <code>%jobtitle%</code>, <code>%%otherinfo%%</code>', 'wp-team-manager'); ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="primary_color"><?php _e('Primary Color','wp-team-manager');?></label></th>
                <td class="">
                    <input name="wtm_settings[primary_color]" id="primary_color" type="text" value="<?php echo $tm_primary_color; ?>" class="tm-color">
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tm_custom_css"><?php _e('CSS','wp-team-manager');?></label></th>
                <td class="">
                <textarea name="wtm_settings[custom_css]" id="tm_custom_css" class="wp-editor-area" rows="10" cols="80"><?php echo $tm_custom_css; ?></textarea>  
                <p class="description"><?php _e('Add custom CSS for Team Manager', 'wp-team-manager'); ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"></th>
                <td class="">
                <?php submit_button(); ?>
                </td>
            </tr>
            

		    </table>

            
            </form>

        <!-- Support -->
        <div id="wptm_support">
            <h3><?php _e('Support & bug report', 'wp-team-manager'); ?></h3>
            <p><?php printf(__('If you have some idea to improve this plugin or any bug to report, please email me at : <a href="%1$s">%2$s</a>', 'wp-team-manager'), 'mailto:info@dynamicweblab.com?subject=[wp-team-manager]', 'info@dynamicweblab.com'); ?></p>
            <p><?php printf(__('You like this plugin ? Then please provide some support by <a href="%1$s" target="_blank">voting for it</a> and/or says that <a href="%2$s" target="_blank">it works</a> for your WordPress installation on the official WordPress plugins repository.', 'wp-team-manager'), 'http://wordpress.org/plugins/wp-team-manager/', 'http://wordpress.org/plugins/wp-team-manager/'); ?></p>
        </div>

        </div>
        <?php
    }


    /**
     * Options page callback
     */
    public function create_admin_shortcode_generator()
    {
        ?>
    <div class="wrap"><div id="icon-tools" class="icon32"></div>
        <h2><?php _e('Shortcode Generator','wp-team-manager'); ?></h2>
        <div id="shortcode_options_wrapper">
          <form id="tm_short_code">
          <table class="form-table">

          <tr valign="top">
                <th scope="row"><label for="tm_cat"><?php _e('Select Team Group:','wp-team-manager'); ?></label></th>
                <td class="">
                <select name="tm_cat" id="tm_cat">
                          
                          <option value="0">All Group</option>
                         <?php 
               
                          $team_groups = get_terms("team_groups");
                          if ( ! empty( $team_groups ) && ! is_wp_error( $team_groups ) ){
                            
                            foreach ( $team_groups as $term ) {
                               echo "<option value='".$term->slug."'>".$term->name."</option>";
                              }
                            
                            }
                       
                       ?> 
                       </select> 
                <p class="description"><?php _e('If selected team member only show from this group.', 'wp-team-manager'); ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tm_orderby"><?php _e('Order By:','wp-team-manager'); ?></label></th>
                <td class="">
                <select id="tm_orderby" name="tm_orderby">
                  <option value="menu_order">Default</option>
                  <option value="title">Name</option>
                  <option value="ID">ID</option>
                  <option value="date">Date</option>
                  <option value="modified">Modified</option>
                  <option value="rand">Random</option>
                </select>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tm_limit"><?php _e('Number of entries to display:','wp-team-manager'); ?> </label></th>
                <td class="">
                <input id="tm_limit" class="regular-text" type="number" value="-1" />
                <p class="description">-1 to show all team members</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tm_show_id">Show this ids only (Example: 1,2,3): </label></th>
                <td class="">
                <input id="tm_show_id" class="regular-text" type="text" value="" />
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tm_show_id">Show this ids only: </label></th>
                <td class="">
                <input id="tm_show_id" class="regular-text" type="text" value="" />
                <p class="description">(Example: 1,2,3)</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tm_remove_id">Remove ids from list: </label></th>
                <td class="">
                <input id="tm_remove_id" class="regular-text" type="text" value="" />
                <p class="description">(Example: 1,5,7)</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tm_layout"><?php _e('Select Layout:','wp-team-manager'); ?></label></th>
                <td class="">
                <select id="tm_layout" name="tm_layout">
                  <option value="grid">Grid</option>
                  <option value="list">List</option>
                </select>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tm_column">Number of column: </label></th>
                <td class="">
                <input id="tm_column" class="regular-text" type="number" value="2" />
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tm_image_layout"><?php _e('Select image style:','wp-team-manager'); ?></label></th>
                <td class="">
                <select id="tm_image_layout" name="tm_layout">
                  <option value="rounded">Rounded</option>
                  <option value="circle">Circle</option>
                  <option value="boxed">Boxed</option>
                </select>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tm_image_size"><?php _e('Select image size:','wp-team-manager'); ?></label></th>
                <td class="">
                <?php  $wtm_image_sizes = get_intermediate_image_sizes();?>
                <select id="tm_image_size" name="tm_image_size">
                  <?php foreach ($wtm_image_sizes as $key => $value): ?>
                    <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, 'medium' ); ?>><?php echo esc_html( ucfirst(str_replace('_',' ',$value)) ) ; ?></option>
                  <?php endforeach; ?>
                </select>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tm_primary_color"><?php _e('Primary Color:','wp-team-manager'); ?></label></th>
                <td class="">
                <input id="tm_primary_color" type="text" value="" />
                <p class="description">Primary color of the layout.</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="tm_hide_info"><?php _e('Hide Team Member Information?','wp-team-manager'); ?></label></th>
                <td class="">
                <input id="tm_hide_info" type="checkbox" value="0" /> Yes
                <p class="description">Hide Team Member Information.</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"></th>
                <td class="">
                <textarea id="shortcode_output_box" rows="5" cols="10" class="large-text code">[team_manager category='0' orderby='menu_order' limit='-1' post__in='' exclude='' layout='grid' column='2' image_layout='rounded' image_size='medium']</textarea>
                <p class="description">Click to copy the shortcode.</p>            
              </td>
            </tr>



          </table>

        </form> 

    </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'tm-settings-group', // Option group
            'wtm_settings' // Option name
        );

    }

}

if( is_admin() )
    $teamManagerSettingsPage = new TeamManagerSettingsPage();