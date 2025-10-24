<article class="identity-card">
    <?php the_custom_logo(); ?>
    <h1><?php bloginfo('name'); ?></h1>
    <h2><?php bloginfo('description'); ?></h2>
    <ul>
        <li><a href="#"><?= decode_getIcon('twitter') ?></a></li>
        <li><a href="#"><?= decode_getIcon('facebook') ?></a></li>
        <li><a href="#"><?= decode_getIcon('google') ?></a></li>
        <li><a href="#"><?= decode_getIcon('linkedin') ?></a></li>
    </ul>
</article>