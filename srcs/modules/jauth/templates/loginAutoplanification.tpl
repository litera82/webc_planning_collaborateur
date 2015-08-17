{meta_html css $j_basepath.'design/front/css/layout.css'}
{meta_html css $j_basepath.'design/front/css/commun.css'}
{meta_html js $j_basepath.'design/front/js/jquery-1.3.2.min.js'}
{*<!--<div id="auth_login_zone">
{if ! $isLogged}
<form action="{jurl 'jauth~login:in'}" method="post" id="loginForm" style="text-align:center;width:290px;margin:260px 350px 45px;position:absolute;">
<img src="{$j_basepath}design/commun/images/logo.gif" title="Agenda - form2plus" alt="Agenda - form2plus">
 	  <fieldset>
      <table>
       <tr>
           <th><label for="login">{@jauth~auth.login@}</label></th>
        <td><input type="text" name="login" id="login" size="27" value="{$login}" /></td>
       </tr>
       <tr>
           <th><label for="password">{@jauth~auth.password@}</th>
        <td><input type="password" name="password" id="password" size="27" /></td>
       </tr>
       {if $showRememberMe}
       <tr>
           <th><label for="rememberMe">{@jauth~auth.rememberMe@}</th>
        <td><input type="checkbox" name="rememberMe" id="rememberMe" value="1" /></td>
       </tr>
       {/if}
       </table>
       <input type="submit" value="Se connecter"/>
       </fieldset>
	   <span>
			Bienvenue sur Forma2+<br />
			Veuillez entrer votre identifiant / mot de passe pour s'autoplannifier
	   </span> 
		{if $failed}
		<p style="color:red;">{@jauth~auth.failedToLogin@}</p>
		{/if}
   </form>
{else}
    <p>{$user->surname} {$user->name}</p>
{/if}
</div>-->*}

<div class="main-page">
		<div class="inner-page">
			<p style="text-align:center;">
			<img src="{$j_basepath}design/commun/images/logo.gif" title="Agenda - form2plus" alt="Agenda - form2plus">
			</p>
			<div class="content">
            	<div class="bloclogin">
                	<h2>Bienvenue sur Forma2+<br />Veuillez entrer votre identifiant / mot de passe pour s'autoplannifier</h2>
                    <form action="{jurl 'jauth~login:in'}" method="post" id="loginForm">
                    	<p class="clear">
                        	<label>{@jauth~auth.login@}</label>
                            <input type="text" class="text" id="txtname" name="login">
                        </p>
                    	<p class="clear">
                        	<label>{@jauth~auth.password@}</label>
                            <input type="password" class="text" id="txtpwd" name="password">
                        </p>
					<input type="submit" style="border: 1px solid #1E364E;border-radius: 5px 5px 5px 5px;margin-left:201px;margin-top:15px;color: #1E364E;cursor: pointer;font-size: 1.1em;font-weight: bold;padding: 6px 5px;" value="Se connecter">
					</form>
					{if $failed}
	                    <a class="forgot" style="color: red; float: right; margin-right: 30px; margin-top: 5px;">{@jauth~auth.failedToLogin@}</a><br />
					{/if}
                </div>
            </div>
	  	</div>
	</div>