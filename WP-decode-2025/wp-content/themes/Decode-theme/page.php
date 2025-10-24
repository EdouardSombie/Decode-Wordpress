<?php get_header() ?>

<main class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1><?php the_title() ?></h1>
                <?php the_content() ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer() ?>