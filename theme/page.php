<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/templates/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

$timber_post     = new Timber\Post();
$context['post'] = $timber_post;
$template = 'default';

// Prepare sub menu.
$menu_items = array_merge($context['menu']['main'], $context['menu']['meta']);

$menu_current = $context['site']->getMenuItemByPostId($menu_items, $post->ID);
$menu_parents = array_reverse($context['site']->getParents($menu_items, $post->ID));

// Setup sub menu.
if (isset($menu_parents[0])) {
    $context['sub_menu'] = $menu_parents[0]->children;
}
else if ($menu_current->children) {
    $context['sub_menu'] = $menu_current->children;
}


// Prepare breadcrumbs
if (isset($menu_parents[1])) {
  $context['breadcrumbs'] = [];


  foreach ($menu_parents as $mp) {
    $context['breadcrumbs'][] = [
      'title' => $mp->title,
      'url' => $mp->url
    ];
  }
}

switch ($timber_post->id) {
    case 46:
        $template = 'home';
        break;


    case 5432: // Named gifts
        $template = 'named-gifts';
        $context['fellowship_groups'] = $timber_post->get_field('fellowship_groups');
        break;

}


Timber::render("page/$template.twig", $context);
