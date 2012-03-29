<?php

namespace PME\SEO;

class Sitemap
{
    private $rootNode;
    private $classFilterType;
    private $classFilterArray;

    private $dom;

    public function __construct()
    {
        $ini = \eZINI::instance( 'pme_seo.ini' );
        $blockName = 'SearchEngineOptimisationSettings';
        $this->rootNode = $ini->variable( $blockName, 'RootNode' );
        $this->classFilterType = trim( $ini->variable( $blockName, 'ClassFilterType' ) );
        $this->classFilterArray = $ini->variable( $blockName, 'ClassFilterArray' );

        $this->dom = new \DOMDocument( '1.0', 'UTF-8' );

        $root = $this->dom->createElement( 'urlset' );
        $root->setAttribute( "xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9" );
        $root = $this->dom->appendChild( $root );
    }

    public function generate()
    {
        $params = array( 'MainNodeOnly' => true );
        if( strlen($this->classFilterType) )
        {
            $params['ClassFilterType'] = $this->classFilterType;
            $params['ClassFilterArray'] = $this->classFilterArray;
        }
        $nodes = \eZContentObjectTreeNode::subTreeByNodeID( $params, $this->rootNode );
        foreach( $nodes as $node )
        {
            $sitemapURL = new SitemapURL( $node );
            $this->addURL( $sitemapURL );
        }
        return $this->dom->saveXML();
    }

    private function addURL( SitemapURL $siteMapURL )
    {
        $url = $this->dom->createElement( 'url');
        $this->dom->getElementsByTagName('urlset')->item(0)->appendChild( $url );

        $loc = $this->dom->createElement( 'loc' , $siteMapURL->loc );
        $url->appendChild( $loc );

        $lastmod = $this->dom->createElement( 'lastmod', $siteMapURL->lastmod );
        $url->appendChild( $lastmod );
    }
}