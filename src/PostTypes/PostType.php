<?php
/**
 * Base class for all controllers.
 */

namespace Tendril\PostTypes;

abstract class PostType
{
  /**
   * Identify yourself.
   */
  abstract public function label();
  /**
   * Register the post type and related taxonomy.
   */
  abstract public function register();

  /**
   * Add post type specific shortcodes.
   */
  public function addShortCodes() {}

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
