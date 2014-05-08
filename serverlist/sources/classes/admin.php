<?php
class admin
{
	protected $registry;
	protected $DB;
	protected $settings;
	protected $request;
	protected $lang;
	protected $member;
	protected $memberData;
	protected $cache;
	protected $caches;
	public function __construct( ipsRegistry $registry )
	{
		/* Make objects */
		$this->registry = $registry;
		$this->DB	    = $this->registry->DB();
		$this->settings =& $this->registry->fetchSettings();
		$this->request  =& $this->registry->fetchRequest();
		$this->lang	    = $this->registry->getClass('class_localization');
		$this->member   = $this->registry->member();
		$this->memberData =& $this->registry->member()->fetchMemberData();
		$this->cache	= $this->registry->cache();
		$this->caches   =& $this->registry->cache()->fetchCaches();
	}
	public function getServerList()
	{
		$this->DB->query("SELECT * FROM ".ipsRegistry::dbFunctions()->getPrefix()."serverlist");	
		$this->DB->execute();
		while($row = $this->DB->fetch())
		{
			$servers[] = $row;
		}
		return $servers;
	}
	public function addServer($server)
	{
		$this->DB->query("INSERT INTO ".ipsRegistry::dbFunctions()->getPrefix()."serverlist SET  `server_ip` = '" . $server['server_ip'] . "', `server_port` = '" . $server['server_port'] . "', `server_tag` = '" . $server['server_tag'] . "'");
		$this->DB->execute();
	
	}
	public function removeServer($uid)
	{
		$this->DB->query("DELETE FROM ".ipsRegistry::dbFunctions()->getPrefix()."serverlist WHERE `uid` = " .intval($uid));
		$this->DB->execute();
	
	
	}
	public function updateServers()
	{
		include_once("SampQueryAPI.php");
		$this->DB->query("SELECT * FROM ".ipsRegistry::dbFunctions()->getPrefix()."serverlist WHERE `server_lastupdate` < UNIX_TIMESTAMP()-20");
		$this->DB->execute();
		while($row = $this->DB->fetch())
		{
			$servers[] = $row;
		}
		if($servers)
		{
		foreach($servers as $row)
		{
			$query = new SampQueryAPI($row['server_ip'], $row['server_port']);
			if($query->isOnline())
			{
					$vars = $query->getInfo();
					$this->DB->query("UPDATE ".ipsRegistry::dbFunctions()->getPrefix()."serverlist SET `server_name` = '" . mysql_escape_string($vars['hostname']) . "', `server_mplayers` = " . $vars['maxplayers'] . ", `server_oplayers` = " . $vars['players'] . ", `server_lastupdate` = UNIX_TIMESTAMP() WHERE `uid` = " . $row['uid']);
					$this->DB->execute();
			}
			else
			{
			
				$this->DB->query("UPDATE ".ipsRegistry::dbFunctions()->getPrefix()."serverlist SET `server_name` = CONCAT('Serwer wyłączony: ', `server_tag`), `server_mplayers` = 0, `server_oplayers` = 0, `server_lastupdate` = UNIX_TIMESTAMP() WHERE `uid` = " . $row['uid']);
				$this->DB->execute();
			}
		
		}
		}
	
	}
	public function generateList()
	{
		$this->updateServers();
		$template = $this->registry->getClass('output')->getTemplate('serverlist')->mainList($this->getServerList());
		
		return $template;
	}
	
}