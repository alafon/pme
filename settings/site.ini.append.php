<?php /* #?ini charset="utf-8"?

[TemplateSettings]
ExtensionAutoloadPath[]=pme

[RegionalSettings]
TranslationExtensions[]=pme

[RoleSettings]
PolicyOmitList[]=seo/sitemap

# Cache item entry (for eZ Publish 4.3 and up)
[Cache]
CacheItems[]=pme_seo

[Cache_pme_seo]
name=Pimp My eZ SEO cache
id=pme-seo
tags[]=content
tags[]=seo
path=sitemap
isClustered=true

[Event]
Listeners[]=content/cache@\PME\SEO\Sitemap::expire

*/ ?>