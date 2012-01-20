<?php

namespace PME\Analytics;

/**
 * This class implements methods used by template operators related to
 * analytics features.
 */
class Operators
{

    const ConfigFile = 'pme_analytics.ini';
    const ParameterDefaultType = 'string';

    /**
     * Returns parameters (from the config file) for the trackers
     *
     * $params is a hash where :
     * - (string) tracker [optionnal] can be used to get parameters for a
     *   specific operator. If not set, then parameters for the first tracker in
     *   the list (see config file) will be returned
     * - other_parameters [optionnal] can be used to specify which parameters
     *   from the config file you want to be loaded. If not set, only
     *   TrackerConfig values will be returned
     *
     * @param array $params
     * @return boolean
     */
    static public function _get_tracker_parameters( $params )
    {

        $ini = \eZINI::instance( self::ConfigFile );
        $trackers = $ini->variable( 'GeneralSettings', 'AnalyticSystems' );

        $configParameters= array();

        if( empty( $trackers ) )
        {
            throw new PME\Exception ( "Analytics trackers not configured" );
        }

        $defaultTracker = $trackers[0];
        $trackerName = !isset( $params['tracker'] ) ? $defaultTracker : $params['tracker'];

        $eZCurrentSiteAccess = \eZSiteAccess::current();
        $currentSiteAccess = $eZCurrentSiteAccess['name'];
        $trackerSettingsBlocname = "{$trackerName}Settings";

        foreach( $ini->variable( $trackerSettingsBlocname, "TrackerConfig" ) as $rawParameter )
        {
            @list( $parameterName, $matchSiteaccess, $parameterValue, $parameterType ) = explode( ";", $rawParameter );
            if ( $matchSiteaccess == $currentSiteAccess or $matchSiteaccess == '' )
            {
                $configParameters[$parameterName]['value'] = $parameterValue;
                $configParameters[$parameterName]['type'] = $parameterType;
            }
        }

        $retValue = array( 'config' => $configParameters );

        if( isset( $params['other_parameters'] ) )
        {
            $otherParameters = $params['other_parameters'];
            foreach( $otherParameters as $otherParameterName )
            {
                $retValue['other_parameters'][$otherParameterName] = $ini->variable( $trackerSettingsBlocname, $otherParameterName );
            }
        }

        return $retValue;
    }
}

?>
