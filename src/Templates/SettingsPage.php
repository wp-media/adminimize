<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*- ?>
<div class="wrap">
    <h1><?php echo $this->page_title; ?></h1>
    <p><?php echo esc_html__( 'Visually compresses the administrative meta-boxes so that more admin page content can be initially seen. The plugin that lets you hide ‘unnecessary’ items from the WordPress administration menu, for all roles of your install. You can also hide post meta controls on the edit-area to simplify the interface. It is possible to simplify the admin in different for all roles.', 'adminimize' ) ?></p>

    <div class="nav-tabs">
        <ul>
            <?php foreach($tabs as $key => $tab): ?>
                <li>
                    <a href="#tab-<?php echo $key ?>">
                        <?php echo $tab->getTitle() ?>
                        <?php if ($tab->getSubTitle()): ?>
                            <span><?php echo $tab->getSubTitle() ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content-holder">
            <?php foreach($tabs as $key => $tab): ?>
                <div id="tab-<?php echo $key ?>" class="tab-content">
                    <?php echo $tab->getContent() ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
