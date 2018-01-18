<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

?>

<div class="wrap">
    <h1><?php echo $this->page_title; ?></h1>

    <div id="nav-tabs">
        <h2 class="nav-tab-wrapper">
            <ul>
            <?php foreach ( $this->tabs as $key => $tab ): ?>
                <li>
                    <a class="nav-tab <?php echo $key === 0 ? 'nav-tab-active' : ''; ?>" href="#tab-<?php echo $key; ?>"><?php echo esc_html( $tab->get_tab_title() ); ?> </a>
                </li>
            <?php endforeach; ?>
            </ul>
        </h2>

        <?php foreach ( $this->tabs as $key => $tab ): ?>
            <div id="tab-<?php echo $key; ?>">
                <?php echo $tab->render_tab_content(); ?> 
            </div>
        <?php endforeach; ?>
    </div>
</div>