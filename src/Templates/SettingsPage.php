<?php declare(strict_types=1); // -*- coding: utf-8 -*- ?>

<div class="wrap">
    <h1><?= esc_attr($this->pageTitle); ?></h1>

    <h2 class="nav-tab-wrapper">
        <?php foreach ((array)$this->tabs as $key => $tab) : ?>
            <a class="nav-tab <?php echo $key === 0 ? 'nav-tab-active' : ''; ?>"
                href="#tab-<?= esc_attr($key); ?>">
                <?= esc_html($tab->title()); ?>
            </a>
        <?php endforeach; ?>
    </h2>

    <?php foreach ((array)$this->tabs as $key => $tab) : ?>
        <div id="tab-<?= esc_attr($key) ?>">
            <?= esc_attr($tab->render()) ?>
        </div>
    <?php endforeach; ?>
</div>
