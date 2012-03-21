{if is_set( $current_node )|not}
	{def $current_node = cond( $current_node_id|gt(0), fetch( 'content', 'node', hash( 'node_id', $current_node_id )), false() )}
{/if}
{if $current_node.object.available_languages|contains(ezini('RegionalSettings','Locale'))|not()}
	<div class="alert alert-warning">
		{"<strong>Sorry!</strong> This content is not yet translated in this language, try another one using the buttons above."|i18n('design')}
	</div>
{/if}
{undef}