<?php
class AffiliatesGenerator {
	
	public static function init() {
		add_shortcode( 'affiliates_generator', array( __CLASS__, 'affiliates_generator' ) );
		
	}
	
	public static function affiliates_generator( $atts, $content = null ) {
		global $wpdb;
		
		remove_shortcode( 'affiliates_generator' );
		$content = do_shortcode( $content );
		add_shortcode( 'affiliates_generator', array( __CLASS__, 'affiliates_generator' ) );
		
		wp_enqueue_style( 'affgenerator' );
		
		$generated = false;
		if ( isset( $_REQUEST['generate'] ) ) {
			$generated = true;
		}
		
		$output = "";
		$output .= '<form action="" method="post">';
		$output .= '<div class="ag-page-url">';
		$output .= '<span>' . __( "Page url:", AFFGENERATOR_DOMAIN ) . '</span>';
		$output .= '<input type="text" name="url" placeholder="' . apply_filters('affiliates_generator_url_placeholder', get_site_url()) . '">';
		$output .= '<input type="submit" name="generate" value="' . __( "Generate", AFFGENERATOR_DOMAIN ) . '">';
		$output .= '</div>';
		if ( $generated ) {
			$output .= '<div class="ag-referral-link">';
			$url = ( isset( $_REQUEST['url'] ) && $_REQUEST['url']!="" )? $_REQUEST['url']:get_site_url();
			$url = self::generate_url( $url );
			$output .= '<span>' . __( "Referral link:", AFFGENERATOR_DOMAIN ) . '</span>';
			$output .= '<input type="text" name="affurl" value="' . $url . '" readonly>';
			$output .= '<div class="ag-description">' . __("(now copy this referral link and share it)", AFFGENERATOR_DOMAIN) . '</span>';
			$output .= '</div>';
		}
		$output .= '</form>';
		
		return $output;
	}
	
	public static function generate_url ( $content ) {
		global $wpdb;
		
		$pname = get_option( 'aff_pname', AFFILIATES_PNAME );
		
		$output = "";
		$user_id = get_current_user_id();
		if ( $user_id && affiliates_user_is_affiliate( $user_id ) ) {
			$affiliates_table = _affiliates_get_tablename( 'affiliates' );
			$affiliates_users_table = _affiliates_get_tablename( 'affiliates_users' );
			if ( $affiliate_id = $wpdb->get_var( $wpdb->prepare(
				"SELECT $affiliates_users_table.affiliate_id FROM $affiliates_users_table LEFT JOIN $affiliates_table ON $affiliates_users_table.affiliate_id = $affiliates_table.affiliate_id WHERE $affiliates_users_table.user_id = %d AND $affiliates_table.status = 'active'",
				intval( $user_id )
			))) {
				$encoded_affiliate_id = affiliates_encode_affiliate_id( $affiliate_id );
				if ( strlen( $content ) == 0 ) {
					$base_url = get_bloginfo( 'url' );
				} else {
					$base_url = $content;
				}
				$separator = '?';
				$url_query = parse_url( $base_url, PHP_URL_QUERY );
				if ( !empty( $url_query ) ) {
					$separator = '&';
				}
				$output .= $base_url . $separator . $pname . '=' . $encoded_affiliate_id;
			}
		}
		return $output;
	}
	
	
}
?>
