<?php

class EditorButton
{
	// admin head
	function admin_head()
	{
		if ( true === is_admin() ){
	        if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) )  return;
	 
	        // check if WYSIWYG is enabled
	        if ( 'true' == get_user_option( 'rich_editing' ) ) {
	            add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
	            add_filter( 'mce_buttons', array($this, 'mce_buttons' ) );
	        }
		}
    }
	
	// embed plugins	
    function mce_external_plugins( $plugin_array )
    {
        $plugin_array[$this->shortcode_tag] = plugins_url( 'js/mce-button.js', __FILE__);
        return $plugin_array;
    }

    function mce_buttons( $buttons )
    {
        array_push( $buttons, $this->shortcode_tag );
        return $buttons;
    }
}