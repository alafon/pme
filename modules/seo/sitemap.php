<?php

$ini = eZINI::instance();

// filepath
$currentSiteaccess = eZSiteAccess::current();
$cacheFilePath = eZSys::cacheDirectory() . '/sitemap/sitemap_' . $currentSiteaccess['name'] . '.xml';

// cache configuration
$seoIni = eZINI::instance( 'pme_seo.ini' );
$cacheTime = intval( $seoIni->variable( 'SearchEngineOptimisationSettings', 'CacheTime' ) );

$sitemapGenerator = new \PME\SEO\Sitemap();

if( $cacheTime <= 0 )
{
    $xml = $sitemapGenerator->generate();
}
else
{
    if ( !is_dir( dirname( $cacheFilePath ) ) )
    {
        eZDir::mkdir( dirname( $cacheFilePath ), false, true );
    }

    // 'cluster aware' implementation
    $cacheFile = eZClusterFileHandler::instance( $cacheFilePath );

    // @todo use $cacheFile->processCache() instead
    if ( !$cacheFile->exists() or ( time() - $cacheFile->mtime() > $cacheTime ) )
    {
        $xml = $sitemapGenerator->generate();
        $cacheFile->storeContents( $xml, 'sitemap', 'xml' );
    }
    else
    {
        $xml = $cacheFile->fetchContents();
    }
}

header( 'Content-Type: application/xml;' );

echo $xml;
eZExecution::cleanExit();
