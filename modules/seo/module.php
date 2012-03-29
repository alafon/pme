<?php

$Module = array( 'name' => 'Pimp My eZ | SEO' );

$ViewList = array();
$ViewList['sitemap'] = array(
    'functions' => array( 'read' ),
    'script' => 'sitemap.php' );

$FunctionList = array();
$FunctionList['read'] = array();