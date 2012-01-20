{def $param_info = array()
     $raw_value = ''
     $value_type = ''}

{def $trackerParameters = pme( 'analytics', 'get_tracker_parameters',
                               hash( 'tracker', 'ga',
                                     'other_parameters', array( 'CrossDomainTracking', 'CrossDomainTrackingClass' ) ))}

<script type="text/javascript">

  var _gaq = _gaq || [];

  {foreach $trackerParameters.config as $param_id => $param_infos}
    {switch match=$param_infos.type}
      {case match='boolean'}
  _gaq.push(['_set{$param_id}', {$param_infos.value}]);
      {/case}
      {case}
  _gaq.push(['_set{$param_id}', '{$param_infos.value}']);
      {/case}
    {/switch}
  {/foreach}
  _gaq.push(['_trackPageview']);

  (function() {ldelim}
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  {rdelim})();

  {*
      Allow cross domain tracking
      See : http://code.google.com/apis/analytics/docs/gaJS/gaJSApiDomainDirectory.html
            http://www.closed-loop-marketing.com/blog/2009/12/31/cross-domain-sub-domain-tracking-with-google-analytics/
  *}
  {if $trackerParameters.other_parameters.CrossDomainTracking|eq( 'enabled' )}
    $(document).ready( function() {ldelim}
        $("a.{$trackerParameters.other_parameters.CrossDomainTrackingClass}").click( function() {ldelim}
           _gaq.push(['_link', this.href ]);
        {rdelim});
        {*
        For form, according to Google, it should look like this but I have not tried it yet so commenting it
        $("form.{$trackerParameters.other_parameters.CrossDomainTrackingClass}").click( function() {ldelim}
           _gaq.push(['_linkByPost', this ]);
        {rdelim});
        *}
    {rdelim});
  {/if}

</script>