<?php
class GP_Route_Profile extends GP_Route_Main {

	function profile_get() {
		if ( ! is_user_logged_in() ) {
			$this->redirect( gp_url( '/login?redirect_to=' ).urlencode( gp_url( '/profile' ) ) );
			return;
		}

		$this->tmpl( 'profile' );
	}

	function profile_post() {
		if ( isset( $_POST['submit'] ) ) {
			$per_page = (int) $_POST['per_page'];
			update_user_meta( get_current_user_id(), 'gp_per_page', $per_page );

			$default_sort = $_POST['default_sort'];
			update_user_meta( get_current_user_id(), 'gp_default_sort', $default_sort );
		}

		$this->redirect( gp_url( '/profile' ) );
	}

	public function profile_view( $user ) {
		$user = GP::$user->find_one( array( 'user_nicename' => $user ) );

		if ( ! $user ) {
			return $this->die_with_404();
		}

		$recent_projects = $user->get_recent_translation_sets( 5 );
		$locales         = $user->locales_known();

		//validate to
		$permissions = $user->get_permissions();

		$this->tmpl( 'profile-public', get_defined_vars() );
	}

}
