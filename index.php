<header>
    <title>SSH Panel</title>
<header>

<?php
if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
if (!empty($_POST['ran'])) {
    $ipaddy=$_POST['ip'];
    $user=$_POST['username'];
    $pass=$_POST['password'];
    $cmd=$_POST['command'];

    if (!empty($ipaddy)) {
        if (!empty($user)) {
            if (!empty($pass)) {
                if (!empty($cmd)) {
                    if(($validconnection = ssh2_connect($ipaddy, 22))){
                        if (ssh2_auth_password($validconnection, $user, $pass)) {
                            
                            if (!($stream = ssh2_exec($validconnection, $cmd ))) {
                                $output="Unable to execute command.";
                            } else {
                                $output="Command successfully executed.";
                                stream_set_blocking($stream, true);
                                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                                $output=stream_get_contents($stream_out);
                                $data = "";
                                while ($buf = fread($stream,4096)) {
                                    $data .= $buf;
                                }
                                if (empty($output)) { $output="Command successfully executed."; }
                                fclose($stream);
                            }

                        } else { $output="Unable to authenticate login credentials."; }
                    } else { $output="Invalid IP"; }
                } else { $output="Invalid command."; }
            } else { $output="Invalid password."; }
        } else { $output="Invalid username."; }
    } else { $output="Invalid IP Address"; }
} else { }

?>

<body>
<link rel="stylesheet" href="style.css">

<form method="post">
    <label>
        IP Address <input type="text" name="ip" value="<?php echo($ipaddy); ?>"/>
    </label>
    <label>
        Username <input type="text" name="username" value="<?php echo($user); ?>"/>
    </label>
    <passlabel>
        <label>Password</label> <input type="password" name="password" value="<?php echo($pass); ?>"/>
    </passlabel>
    <label>
        Command <input type="text" name="command" />
    </label>
    <button type="submit" name="ran" value="true">Execute</button>
</form>

<label>
   Output 
</label>
<pre>
  <code>[Last Executed Command]
<codesent>></codesent><?php echo($cmd) ?>
 <br></br>
<?php echo($output) ?>
  </code>
</pre>

</body>




