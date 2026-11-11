<?php
 echo $this->session->flashdata('saved');
 $sections = array(
     array('id' => 'school', 'title' => 'Configurações da escola', 'items' => $school_menu),
     array('id' => 'manage', 'title' => 'Administração', 'items' => $manage_menu),
 );
?>
<div class="setup-home">
    <?php foreach ($sections as $section): ?>
        <?php if (empty($section['items'])) { continue; } ?>
        <section class="setup-section" aria-labelledby="setup-<?= $section['id'] ?>">
            <h2 id="setup-<?= $section['id'] ?>" class="setup-section-title"><?= html_escape($section['title']) ?></h2>
            <div class="setup-cards setup-cards--<?= $section['id'] ?>">
                <?php foreach ($section['items'] as $link): ?>
                    <a class="setup-card" href="<?= html_escape($link['url']) ?>">
                        <img src="<?= html_escape(base_url('assets/images/ui/' . $link['img'])) ?>" alt="" width="64" height="64">
                        <span class="setup-card-label"><?= html_escape($link['label']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>
</div>
