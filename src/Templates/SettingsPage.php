<?php declare(strict_types=1); // -*- coding: utf-8 -*- ?>

<div class="wrap">
    <h1><?= esc_attr($this->pageTitle); ?></h1>

    <div id="nav-tabs">
        <h2 class="nav-tab-wrapper">
            <ul>
                <?php foreach ((array)$this->tabs as $key => $tab) : ?>
                    <li>
                        <a class="nav-tab <?php echo $key === 0 ? 'nav-tab-active' : ''; ?>"
                            href="#tab-<?= esc_attr($key); ?>">
                            <?= esc_html($tab->title()); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </h2>

        <?php foreach ((array)$this->tabs as $key => $tab) : ?>
            <div id="tab-<?php echo esc_attr($key) ?>">
                <?= esc_attr($tab->render()) ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
