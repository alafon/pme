{default enable_help=true() enable_link=true() canonical_link=true()}
{def $site_title = ''}
{if is_set($module_result.content_info.persistent_variable.site_title)}
    {set $site_title = $module_result.content_info.persistent_variable.site_title}
{else}
    {set $site_title = ''}

    {def $site_title_array = array()
         $path = cond( is_set($pagedata.path_array), $pagedata.path_array,
                       is_set($pagedata.title_path), $pagedata.title_path )}

    {foreach $path as $path_item reverse}
        {if and( is_set( $path_item.node_id ), or( $path_item.node_id|eq( $pagedata.root_node ), and( $path_item.node_id|eq(2), $current_node_id|ne(2) ) ))}{break}{/if}
        {set $site_title_array = $site_title_array|append( $path_item.text|wash  )}
    {/foreach}

    {set $site_title = concat( $site_title_array|implode( ' < '), ' | ', $site.title|wash  )}
    {undef $path $site_title_array}
{/if}

    <title>{$site_title}</title>

    {if and(is_set($#Header:extra_data),is_array($#Header:extra_data))}
      {section name=ExtraData loop=$#Header:extra_data}
      {$:item}
      {/section}
    {/if}

    {* check if we need a http-equiv refresh *}
    {if $site.redirect}
    <meta http-equiv="Refresh" content="{$site.redirect.timer}; URL={$site.redirect.location}" />

    {/if}
    {foreach $site.http_equiv as $key => $item}
        <meta name="{$key|wash}" content="{$item|wash}" />

    {/foreach}
    {foreach $site.meta as $key => $item}
    {if is_set( $module_result.content_info.persistent_variable[$key] )}
        <meta name="{$key|wash}" content="{$module_result.content_info.persistent_variable[$key]|wash}" />
    {else}
        <meta name="{$key|wash}" content="{$item|wash}" />
    {/if}

    {/foreach}

    {* Prefer chrome frame on IE 8 and lower, or at least as new engine as possible *}
    <!--[if lt IE 9 ]>
        <meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1" />
    <![endif]-->

    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <meta name="generator" content="eZ Publish" />

{if $canonical_link}
    {include uri="design:canonical_link.tpl"}
{/if}

{if $enable_link}
    {include uri="design:link.tpl" enable_help=$enable_help enable_link=$enable_link}
{/if}

{undef $site_title}
{/default}