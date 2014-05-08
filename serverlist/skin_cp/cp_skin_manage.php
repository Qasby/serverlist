<?php

/**
 * gamehub
 * 
 * @web http://doside.pl 
 * @author Mateusz Pater
 * @copyright teez@onet.eu [TeeZ]
 * @version 2013
 * @access public
 */
class cp_skin_manage extends output
{
    public function general($servers) 
    {
        $IPBHTML = "";
        //--starthtml--//
if($servers)
{
        $IPBHTML .= <<<HTML
			<table class='ipsTable' id='fields_list'>
	<tr>
		<td>Nazwa</td> <td>Tag</td> <td>Adres ip</td> <td></td>
	</tr>
HTML;

	foreach($servers as $server)
{
	$IPBHTML .= <<<HTML
	<tr>
		<td>{$server['server_name']}</td><td>{$server['server_tag']}</td><td>{$server['server_ip']}</td><td><a href="{$this->settings['base_url']}&uid={$server['uid']}">Usuń</a></td>
	</tr>
HTML;
}
        $IPBHTML .= <<<HTML
</table>
HTML;
}
else
{
$IPBHTML .= "Żaden serwer nie został jeszcze dodany";

}
       return $IPBHTML;
	}
	
	
	
	
	public function addServer($errors) 
	{
		$IPBHTML = "";
        //--starthtml--//
        
       
		$IPBHTML .= <<<HTML
<table>
<tbody>
<form method="post">
	<tr>
		<td><b>Nazwa serwera:</b></td>
		<td style="padding-left:5%;"><input type="text" name="server_tag" class="input_text"></td>
	</tr>
	<tr>
		<td><b>Adres ip:</b></td>
		<td style="padding-left:5%;">
			<input type="text" name="server_ip" class="input_text">
		</td>
	</tr>
	<tr>
		<td ><b>Port:</b></td>
		<td style="padding-left:5%;">
			<input type="text" name="server_port" class="input_text" maxlength="4" size="4">
		</td>
	</tr>
	<tr>
		<td colspan="2"><input style="margin-top:20px;margin-left:15px;" type="submit" name="submit" value="Dodaj serwer"></td>
		
	</tr>
</form>
</tbody>
</table>


HTML;
if($errors)
{
	foreach($errors as $error)
	{
		$IPBHTML .= <<<HTML
		{$error}
		
		
HTML;
	
	}

}
	    return $IPBHTML;
	}
}