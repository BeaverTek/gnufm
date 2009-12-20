<!DOCTYPE html>
<html>
<head>
	{if !($pagetitle)}
	<title>Libre.fm &mdash; {t}discover new music{/t}</title>
	{else}
	<title>{$pagetitle|escape:'html':'UTF-8'} &mdash; Libre.fm &mdash; {t}discover new music{/t}</title>
	{/if}
	<meta name="author" content="FooCorp catalogue number FOO200 and contributors" />
{section name=i loop=$extra_head_links}
	<link rel="{$extra_head_links[i].rel|escape:'html':'UTF-8'}" href="{$extra_head_links[i].href|escape:'html':'UTF-8'}" type="{$extra_head_links[i].type|escape:'html':'UTF-8'}" title="{$extra_head_links[i].title|escape:'html':'UTF-8'}"  />
{/section}
</head>

<body>

<div id="doc3">
	<div id="hd">
		<h1>{if ($logged_in)}
	<a href="{$this_user->getURL()}">{$this_user->name}</a>'s&nbsp;
	{/if}
<a href="{$base_url}">Libre.fm</a></h1>


		{include file='menu.tpl'}
	</div>

   <div id="bd">
<div class="yui-g">

<p><b>We're doing some work on the site, regular design will return shortly.</b></p>

{if !empty($errors)}
<div id="errors">
	<p>{$errors}</p>
</div>
{/if}
