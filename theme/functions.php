<?php

use Tendril\Site;


// Load and configure Timber.
$timber = new Timber\Timber();

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = ['resources/views'];

/**
 * By default, Timber does NOT autoescape values
 */
Timber::$autoescape = false;

// Configure and initialize the site.
$site = new Site();
