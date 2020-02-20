<?php
/**
 * Base class for all controllers.
 */

namespace Tendril\Controllers;

abstract class Controller
{
  // protected $core;
  // protected $filters_allowed = [];

  /**
   * Identify yourself.
   */
  abstract public function label();
  /**
   * Register the post type and related taxonomy.
   */
  abstract public function registerPostType();

  /**
   * Register the related taxonomy.
   */
  public function registerTaxonomy() {}

  /**
   * Add post type specific shortcodes.
   */
  public function addShortCodes() {}

  public function filterRedirect()
  {
    $filters = [];
    $redirect = wp_make_link_relative($_POST['redirect']);

    foreach ($_POST as $name => $value) {
      if (!empty($value) && in_array($name, $this->filters_allowed)) {
        if (is_array($value)) {
          $filters[$name] = implode('+', esc_sql($value));
        }
        else {
          $filters[$name] = esc_sql($value);
        }
      }
    }

    if ($filters) {
      // Check, if redirect has a hashtag.
      $red_tokens = explode('#', $redirect);

      if (count($red_tokens) > 1) {
        $redirect = $red_tokens[0] . '?' . http_build_query($filters) . '#' . $red_tokens[1];
      }
      else {
        $redirect .= '?' . http_build_query($filters);
      }
    };

    wp_redirect($redirect);
    exit();
  }


  // public function view(Post $model, $view = 'view')
  // {
  //   $vars['post'] = $model;

  //   $template = $this->label() . '/' . $view . '.twig';

  //   echo Tendril::render($template, $vars);
  // }

  public function getPaginationLinks($wp_query = null)
  {
    if ( !$wp_query ) {
      global $wp_query;
    }

    $paged = isset($wp_query->query_vars['paged']) ? $wp_query->query_vars['paged'] : get_query_var('paged');
    $ppp = 10;
    if ( isset($wp_query->query_vars['posts_per_page']) ) {
      $ppp = $wp_query->query_vars['posts_per_page'];
    }

    $current = isset($wp_query->query_vars['paged']) ? $wp_query->query_vars['paged'] : get_query_var('paged');
    $total = ceil($wp_query->found_posts / $ppp);

    $first = [
      'class' => 'page__first',
      'link' => get_pagenum_link(1, false)
    ];

    $last = [
      'class' => 'page__last',
      'link' => get_pagenum_link($total, false)
    ];

    $next = [
      'class' => 'pager__next',
      'link' => get_pagenum_link($current + 1, false)
    ];

    $prev = [
      'class' => 'pager__prev',
      'link' => get_pagenum_link($current - 1, false)
    ];

    $pagination = [
      'current' => $current,
      'total' => $total,
      'first' => ($current > 1) ? $first : '',
      'last' => ($current < $total) ? $last : '',
      'next' => ($current == $total) ? '' : $next,
      'prev' => ($current == 1) ? '' : $prev
    ];


    for ($i = 1; $i <= $total; $i++) {
      $page = [
        'class' => 'pager__number',
        'title' => $i
      ];

      if ($i == $current) {
        $page['class'] .= ' current';
      }
      else {
        $page['link'] = get_pagenum_link($i, false);
      }

      $pagination['pages'][] = $page;
    }

    return $pagination;
  }

}
