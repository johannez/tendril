<?php
/**
 * Search results page
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$context          = Timber::context();
$context['search_query'] = get_search_query();

$search_results = new Timber\PostQuery();
$context['pagination'] = $search_results->pagination();

foreach ($search_results as $sr) {
    $search_result = [
      'title' => $sr->title,
      'link' => $sr->link,
      'type' => ucwords(str_replace('_', ' ', $sr->post_type))
    ];

    switch ($sr->type) {
      case 'news':
      case 'event':
      case 'story':
        $search_result['summary'] = $sr->summary;
        break;

      case 'person':
        $search_result['title'] = ($sr->salutation ? $sr->salutation : $sr->first_name) . ' ' . $sr->last_name;
        $search_result['summary'] = $sr->biography;
        break;  

      default:
        $search_result['summary'] = $sr->post_content;
        break;

    }

    if ($search_result) {
        // Clean up summary.
        $search_result['summary'] = preg_replace('/class=".*?"/', '', strip_tags(strip_shortcodes($search_result['summary']), '<a><strong><em>'));
        $context['search_results'][] = $search_result;
    }
}

Timber::render('page/search-results.twig', $context);
