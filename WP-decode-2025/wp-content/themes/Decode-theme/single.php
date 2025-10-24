<?php get_header() ?>

<?php
// template des articles

// $post créé par WP
// $currentElement = get_queried_object();
?>

<main>
    <h1><?php the_title() ?></h1>
    <div class="post-meta">
        <div class="post-author">
            <?php echo get_avatar($post->post_author) ?>
            <?php echo get_the_author_meta('nickname', $post->post_author);  ?>
        </div>
        <time><?= wp_date('j F Y', strtotime($post->post_date)) ?></time>
    </div>
    <?php
    echo get_the_post_thumbnail($post, 'large');
    ?>
    <?php
    the_content();
    ?>
</main>

<?php get_footer() ?>