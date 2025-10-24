<?php 
$args = [
    'post_type' => 'post'
];
$posts = get_posts($args);
?>
<ul class="post-list">
<?php foreach($posts as $p){ ?>
    <li>
        <a href="<?= get_permalink($p) ?>"><?= $p->post_title ?></a>
        <time><?= wp_date('j F Y', strtotime($p->post_date) )  ?></time>
    </li>
<?php } ?>
</ul>