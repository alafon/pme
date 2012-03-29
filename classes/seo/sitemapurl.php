<?php

namespace PME\SEO;

class SitemapURL
{
    public $loc;
    public $lastmod;
    public $changefreq;
    public $priority;

    static private $siteURL;

    /**
     *
     * Creates a object based on a content object node
     *
     * @param \eZContentObjectTreeNode $node
     */
    public function __construct( \eZContentObjectTreeNode $node )
    {
        $this->setLoc( $node->attribute( 'url_alias' ) );
        $this->setLastMod( (int) $node->object()->attribute( 'modified' ));
    }

    static private function getSiteURL()
    {
        if( is_null( self::$siteURL ) )
        {
            self::$siteURL = \eZINI::instance()->variable( 'SiteSettings', 'SiteURL' );
        }
        return self::$siteURL;
    }

    /**
     *
     * Converts an urlAlias into a full URL
     *
     * @todo handle https as well
     * @param string $urlAlias
     */
    private function setLoc( $urlAlias )
    {
        $this->loc = "http://" . self::getSiteURL() . "/" . $urlAlias;
    }

    /**
     *
     * Converts the timestamp into a ISO 8601 format
     * @param int $timestamp
     */
    private function setLastMod( $timestamp )
    {
        $this->lastmod = date( 'c', $timestamp );
    }

    /**
     *
     * Not yet implemented
     *
     * @param string $changefreq
     */
    private function setChangeFreq( $changefreq )
    {

    }

    /**
     *
     * Not yet implemented
     *
     * @param string $priority
     */
    private function setPriority( $priority )
    {

    }
}