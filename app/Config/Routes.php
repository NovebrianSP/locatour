<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('category/(:segment)', 'Home::category/$1');
// Search page
$routes->get('search', 'Search::results');
// Evaluation page
$routes->get('evaluation', 'Evaluation::index');

// Recommendation API routes
$routes->group('recs', static function($routes) {
	$routes->get('/', 'Recommender::index');
	$routes->get('hybrid', 'Recommender::hybrid');
	$routes->get('personalized/(:num)', 'Recommender::personalized/$1');
	$routes->get('weather', 'Recommender::weather');
    $routes->get('search', 'Recommender::search');
});