<?php

/**
 * Plugin Name: Decode Plugin
 * Plugin URI:  https://example.com/decode-plugin
 * Description: Plugin WordPress "Decode" — extensions et fonctionnalités pour le site Decode
 * Version:     1.0.0
 * Author:      Edouard / decode
 * Author URI:  https://example.com
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: decode-plugin
 * Domain Path: /languages
 *
 * @package DecodePlugin
 */

if (! defined('ABSPATH')) {
    exit; // Accès direct refusé.
}

// ajout d'un lien de duplication
add_filter('post_row_actions', 'decode_post_row_actions', 10, 2);
function decode_post_row_actions($actions, $post)
{
    if (current_user_can('edit_posts')) {
        $url = wp_nonce_url(
            add_query_arg(
                [
                    'action' => 'decode_duplicate_post',
                    'post' => $post->ID,
                ],
                'admin.php'
            )
        );
        $actions['duplicate'] = '<a href="' . $url . '">Dupliquer</a>';
    }

    return $actions;
}

// prise en charge de l'url de duplication
// admin.php?action=...&post=...
add_action('admin_action_decode_duplicate_post', 'decode_duplicat_post');
function decode_duplicat_post()
{
    // vérifie qu'il existe bien un paramètre get nommé 'post'
    if (!isset($_GET['post'])) {
        return;
    }

    $original_post = get_post($_GET['post']);

    $args = [
        'post_author' => $original_post->post_author,
        'post_date' => date('Y-m-d H:i:s'),
        'post_content' => $original_post->post_content,
        'post_title' => $original_post->post_title . ' - COPIE',
    ];

    $id_duplicate = wp_insert_post($args);
    // id du duplicata utile pour copier les taxonomies et autres meta données

    if (! empty($id_duplicate) && ! is_wp_error($id_duplicate)) {
        wp_safe_redirect(admin_url('edit.php?decode_duplicate'));
        exit;
    }
}

add_action('admin_notices', 'decode_admin_notices');
function decode_admin_notices()
{
    if (isset($_GET['decode_duplicate'])) {
        echo '<div class="notice notice-success is-dismissible"><p>Article copié avec succès !!</p></div>';
    }
}

// Ajouter un type de publication custom (project)

add_action('init', 'decode_init');
function decode_init()
{

    $labels = [
        'name'                  => __('Projets', 'decode-plugin'),
        'singular_name'         => __('Projet', 'decode-plugin'),
        'menu_name'             => __('Projets', 'decode-plugin'),
        'name_admin_bar'        => __('Projet', 'decode-plugin'),
        'add_new'               => __('Ajouter un nouveau', 'decode-plugin'),
        'add_new_item'          => __('Ajouter un nouveau projet', 'decode-plugin'),
        'new_item'              => __('New Project', 'decode-plugin'),
        'edit_item'             => __('Edit Project', 'decode-plugin'),
        'view_item'             => __('View Project', 'decode-plugin'),
        'all_items'             => __('All Projects', 'decode-plugin'),
        'search_items'          => __('Search Projects', 'decode-plugin'),
        'parent_item_colon'     => __('Parent Projects:', 'decode-plugin'),
        'not_found'             => __('No projects found.', 'decode-plugin'),
        'not_found_in_trash'    => __('No projects found in Trash.', 'decode-plugin'),
    ];

    $args = [
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => ['slug' => 'project'],
        'capability_type'       => 'post',
        'has_archive'           => true,
        'hierarchical'          => false,
        'menu_position'         => 2,
        'menu_icon'             => 'dashicons-media-code',
        'supports'              => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields'],
        'taxonomies'            => ['skill'],
        'show_in_rest'          => true, // enable Gutenberg / REST API
    ];

    register_post_type('project', $args);


    $labels_skill = [
        'name'                       => __('Skills', 'decode-plugin'),
        'singular_name'              => __('Skill', 'decode-plugin'),
        'search_items'               => __('Search Skills', 'decode-plugin'),
        'popular_items'              => __('Popular Skills', 'decode-plugin'),
        'all_items'                  => __('All Skills', 'decode-plugin'),
        'parent_item'                => __('Parent Skill', 'decode-plugin'),
        'parent_item_colon'          => __('Parent Skill:', 'decode-plugin'),
        'edit_item'                  => __('Edit Skill', 'decode-plugin'),
        'update_item'                => __('Update Skill', 'decode-plugin'),
        'add_new_item'               => __('Add New Skill', 'decode-plugin'),
        'new_item_name'              => __('New Skill Name', 'decode-plugin'),
        'separate_items_with_commas' => __('Separate skills with commas', 'decode-plugin'),
        'add_or_remove_items'        => __('Add or remove skills', 'decode-plugin'),
        'choose_from_most_used'      => __('Choose from the most used skills', 'decode-plugin'),
        'not_found'                  => __('No skills found.', 'decode-plugin'),
        'menu_name'                  => __('Skills', 'decode-plugin'),
    ];

    $args_skill = [
        'hierarchical'          => false,
        'labels'                => $labels_skill,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'query_var'             => true,
        'rewrite'               => ['slug' => 'skill'],
        'show_in_rest'          => true, // support Gutenberg / REST API
    ];

    register_taxonomy('skill', ['project'], $args_skill);
}


// Déterminer le chemin par défaut du template d'un projet seul
add_filter('template_include', 'decode_template_include');
function decode_template_include($template)
{
    if (!file_exists(get_stylesheet_directory() . '/single-project.php') && is_single() && get_query_var('post_type') == 'project') {
        $template = __DIR__ . '/templates/single-project.php';
    }
    return $template;
}


// Ajout d'un shortcode skills-list
add_shortcode('skills-list', 'decode_skills_list');
function decode_skills_list($attr)
{
    $skills = get_terms([
        'taxonomy' => 'skill',
        'hide_empty' => false,
    ]);
    $output = '';
    if (!empty($attr['title'])) {
        $output .= '<h2>' . $attr['title'] . '</h2>';
    }
    $output .= '<ul>';
    foreach ($skills as $s) {
        $output .= '<li><a href="' . get_term_link($s) . '">' . $s->name . '</a></li>';
    }
    $output .= '</ul>';
    return $output;
}

add_filter('nav_menu_css_class', function ($classes, $item, $args, $depth) {

    $queried = get_queried_object();
    $blog_id = get_option('page_for_posts');

    if ($item->ID == $blog_id) {
        if (is_post_type_archive('project') || $queried->post_type == 'project') {
        }
    }

    // Ajouter une classe personnalisée
    echo '<pre>';
    var_dump($queried);
    $classes[] = 'custom-class';


    return $classes;
}, 10, 4);
