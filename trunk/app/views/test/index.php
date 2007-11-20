<h1>TestController</h1>

<p>This controller was written with two purposes, in this order of importance</p>
<ol>
	<li>To allow me to test all the funcionality</li>
	<li>To serve as reference to other users (Maybe someone besides me will use this)</li>
</ol>

<form action="<?=url('/test')?>" method="post">
<p>You're currently visiting the index() function of the MechTestController.<br/>
Installation and basic setup instructions are on the header of the index.php file<br/>
I've created a form as an example on how to pass data between requests, so choose the topic and click <input type="submit" value="Go"/></p>
<ul>
	<li><input type="radio" name="destination" value="views"/> About Views, using variables and external functions.</li>
	<li><input type="radio" name="destination" value="forms"/> About Forms and the special <i>$form</i> variable</li>
	<li><input type="radio" name="destination" value="sessions"/> About Sessions, or how to retain values between requests (also accesses parameters from the command line).</li>
</ul>
</form>

<hr/>
<p><i><b>Influx</b> was written in 2007 in about 3 hours by Guilherme Barile (guigouz) due to his CakePHP addiction (just can't code the wrong way).
He needed something light for a tiny application that didn't need all the extraordinary funcionality Cake offered.<br/>
His website is <a href="http://guigo.us">guigo.us</a> (mostly in portuguese), and the Influx homepage is hosted there, under <a href="http://www.guigo.us/projects/influx">/projects/influx</a>, while its code is kindly hosted by google at <a href="http://code.google.com/p/influx">code.google.com/p/influx</a></i></p>