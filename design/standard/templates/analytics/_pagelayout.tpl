{* wrapper for anaytic systems inclusion *}
{foreach ezini( 'GeneralSettings', 'AnalyticSystems', 'pme_analytics.ini' ) as $as_code}
    {include uri=concat( 'design:analytics/pagelayout_', $as_code, '.tpl' )}
{/foreach}