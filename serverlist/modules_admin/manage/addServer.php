<?php
class admin_serverlist_manage_addServer extends ipsCommand
{
	public function doExecute( ipsRegistry $registry )
	{
		//Load Class
		$classToLoad = IPSLib::loadLibrary( IPSLib::getAppDir('serverlist') . '/sources/classes/admin.php', 'admin', 'serverlist' );
        $registry->setClass( 'admin', new $classToLoad( $registry ) );
		$this->admin = $this->registry->getClass('admin');
		
		//Walidacja
		if($_POST)
		{
			$valid['ip'] = filter_var($_POST['server_ip'], FILTER_VALIDATE_IP);
			$valid['port'] = preg_match("/^[0-9]{4}$/D", $_POST['server_port']);
			if(!$valid['ip'])
			{
				$errors[] = "Podany adres ip jest niepoprawny.";
			}			
			else if(!$valid['port'])
			{
				$errors[] = "Podany port jest niepoprawny.";
			} else if (!$errors)
			{
				$this->admin->addServer($_POST);
				$errors[] = "Pomyślnie dodano server";
			
			
			}
		}
		
		
		//view
		$this->html = $this->registry->output->loadTemplate( 'cp_skin_manage' );
		$this->lang->loadLanguageFile( array( 'public' ) );
		$this->form_code = $this->html->form_code = 'module=addServer';
		$this->form_code_js = $this->html->form_code_js = 'module=addServer';
		$this->registry->output->html .= $this->html->addServer($errors);
		/* Output */
		
		$this->registry->output->html_main .= $this->registry->output->global_template->global_frame_wrapper();
		$this->registry->output->sendOutput();
	}
}