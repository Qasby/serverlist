<?php
class admin_serverlist_manage_serverList extends ipsCommand
{
	public function doExecute( ipsRegistry $registry )
	{
		//Load Class
		$classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir('serverlist') . '/sources/classes/admin.php', 'admin', 'serverlist' );
        $registry->setClass( 'admin', new $classToLoad( $registry ) );
		$this->admin = $this->registry->getClass('admin');
		$this->admin->updateServers();
		//remove server
		if($_GET['uid'])
		{
			$valid = preg_match("/^[0-9]$/D", $_GET['uid']);
			if($valid)
			{
				$this->admin->removeServer($_GET['uid']);
			
			}
		
		}
		//view
		$this->html = $this->registry->output->loadTemplate( 'cp_skin_manage' );
		$this->lang->loadLanguageFile( array( 'public' ) );
		$this->form_code = $this->html->form_code = 'module=serverList';
		$this->form_code_js = $this->html->form_code_js = 'module=serverList';
		$this->registry->output->html .= $this->html->general($this->admin->getServerList());
		/* Output */
		
		$this->registry->output->html_main .= $this->registry->output->global_template->global_frame_wrapper();
		$this->registry->output->sendOutput();
	}
}