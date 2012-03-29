<?php

namespace PME\SEO;

class Sitemap
{
    private $rootNode;
    private $classFilterType;
    private $classFilterArray;
    private $excludedNodes;

    private $dom;

    public function __construct()
    {
        $ini = \eZINI::instance( 'pme_seo.ini' );
        $blockName = 'SearchEngineOptimisationSettings';
        $this->setRootNode( trim( $ini->variable( $blockName, 'RootNode' ) ) );
        $this->classFilterType = trim( $ini->variable( $blockName, 'ClassFilterType' ) );
        $this->classFilterArray = $ini->variable( $blockName, 'ClassFilterArray' );
        $this->setExcludedNodes( $ini->variable( $blockName, 'ExcludedNodes' ) );

        $this->dom = new \DOMDocument( '1.0', 'UTF-8' );

        $root = $this->dom->createElement( 'urlset' );
        $root->setAttribute( "xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9" );
        $root = $this->dom->appendChild( $root );
    }

    private function setRootNode( $string )
    {
        // calculates root_node the same way eZPagedata does
        if( $string === 'root_node' )
        {
            $rootNode = (int) \eZINI::instance('content.ini')->variable( 'NodeSettings', 'RootNode' );
            $ini= \eZINI::instance();
            if( $ini->hasVariable( 'SiteSettings', 'RootNodeDepth' ) &&
                     $ini->variable( 'SiteSettings', 'RootNodeDepth' ) !== '0' )
            {
                $rootNode = $ini->variable( 'SiteSettings', 'RootNodeDepth' ) -1;
            }
            $this->rootNode = $rootNode;
        }
        else
        {
            $this->rootNode = $string;
        }
    }

    private function setExcludedNodes( $arrayOfStrings )
    {
        foreach( $arrayOfStrings as $nodeIDString )
            $this->excludedNodes[] = intval( $nodeIDString );
    }

    public function generate()
    {
        // first element to add = the root node (because it's not in the results we use
        // just after
        $this->addURL(
            new SitemapURL(
                \eZContentObjectTreeNode::fetch( $this->rootNode ) ) );

        // the other elements to add = children of the root_node (with some limitations)
        $params = array( 'MainNodeOnly' => true );
        if( strlen($this->classFilterType) )
        {
            $params['ClassFilterType'] = $this->classFilterType;
            $params['ClassFilterArray'] = $this->classFilterArray;
        }
        $nodes = \eZContentObjectTreeNode::subTreeByNodeID( $params, $this->rootNode );

        foreach( $nodes as $node )
        {
            if( array_search( $node->attribute( 'node_id' ), $this->excludedNodes ) === false )
            {
                $sitemapURL = new SitemapURL( $node );
                $this->addURL( $sitemapURL );
            }
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