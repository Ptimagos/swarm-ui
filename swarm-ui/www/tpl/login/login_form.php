<form class="form-4" action="" method="post">
	<?PHP
		if (isset($_POST['submit'])) {
			print "<h1 class=form-4>Utilisateur ou mot de passe incorrect</h1>";
		} else {
			print "<h1 class=form-4>Connexion</h1>";
		}
	?>
	<p>
		<label for="login">Utilisateur</label>
		<input type="text" name="username" placeholder="Utilisateur" required>
	</p>
	<p>
		<label for="password">Mot de passe</label>
		<input type="password" name='password' placeholder="Mot de passe" required> 
	</p>

	<p>
		<input type="submit" name="submit" value="Continue">
	</p>       
</form>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
