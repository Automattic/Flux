<?php

class Flux_Default_Functions {

	public function __construct() {
		$this->setup_actions();
	}

	private function setup_actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'flux-global-js', flux_get_templates_url() . 'js/flux.js', array( 'jquery' ), 0, true );
		wp_enqueue_style( 'flux-global-css', flux_get_templates_url() . 'css/flux.css' );
	}
}
new Flux_Default_Functions();