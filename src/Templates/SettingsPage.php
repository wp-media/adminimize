<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

?>

<div class="wrap">
	<h1><?php echo esc_attr( $this->page_title ); ?></h1>

	<div id="nav-tabs">
		<h2 class="nav-tab-wrapper">
			<ul>
				<?php foreach ( (array) $this->tabs as $key => $tab ) { ?>
					<li>
						<a class="nav-tab <?php echo $key === 0 ? 'nav-tab-active' : ''; ?>"
							href="#tab-<?php echo esc_attr( $key ); ?>">
							<?php echo esc_html( $tab->get_tab_title() ); ?>
						</a>
					</li>
				<?php } ?>
			</ul>
		</h2>

		<?php foreach ( (array) $this->tabs as $key => $tab ) { ?>
			<div id="tab-<?php echo esc_attr( $key ); ?>">
				<?php echo esc_attr( $tab->render_tab_content() ); ?>
			</div>
		<?php } ?>
	</div>
</div>
