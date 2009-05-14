{include file='header.tpl'}

<h2>{t}Password reset{/t}</h2>

{if isset($errors)}
	<p id='errors'>{$errors}</p>
{/if}

{if isset($changed)}
	<p>{t}Your new password has been emailed to you.{/t}</p>
{/if}

{if isset($sent)}
	<p>{t}An email with further information has been sent to the email address associated with your profile.{/t}</p>
	
{else}

<div id='invite'>

	<form action="{$base_url}/reset.php" method='post'>
		<fieldset>
	        <p><a href='http://libre.fm' rel='bookmark' class='vcard fn org url'>libre.fm</a> {t}Password Reset{/t}</p>

			<label for='username'>{t}Username{/t} <span>{t}must be valid!{/t}</span></label>
			<input id='username' name='user' type='text' value='' />

			<input type='submit' name='recover' value='{t}Reset my password!{/t}'/>
		</fieldset>

	</form>
</div>

{/if}

{include file='footer.tpl'}