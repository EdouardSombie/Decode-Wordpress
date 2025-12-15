<?php get_header() ?>
<?php
$skill = get_queried_object();
?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1><?= $skill->name ?></h1>
                <p>
                    <?= $skill->description ?>
                </p>

                <?php
                // Liste des projets utilisant ce skill
                $args = [
                    'post_type' => 'project',
                    'tax_query' => [
                        [
                            'taxonomy' => 'skill',
                            'field' => 'term_id',
                            'terms' => $skill->term_id,
                        ]
                    ],
                    'posts_per_page' => -1,
                ];
                $projects = get_posts($args);

                if (!empty($projects)) {
                    echo '<h2>Projets en lien</h2>';
                    echo '<ul>';
                    foreach ($projects as $p) {
                        echo '<li><a href="' . get_permalink($p) . '">' . get_the_title($p) . '</a></li>';
                    }
                    echo '</ul>';
                }
                ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer() ?>