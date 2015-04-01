<?php

class CalendarController extends IndexController  {
	
	function eventsAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		$config = Zend_Registry::get("config");
		$session = SessionWrapper::getInstance();
		$formvalues = $this->_getAllParams();
		$acl = getACLInstance();
	
		$user = new UserAccount();
		// $user->populate($formvalues['id']);
		$events = getTimeoffRequests("", $config->system->yearstart, $config->system->yearend); // debugMessage($events);
		$jsondata = array();
		$i = 0;
		if(count($events) > 0){
			// $jsondata = $events;
			$timeoffoptions = getHoursDaysDropdown();
			foreach ($events as $key => $value){
				$jsondata[$key]['id'] = $value['id'];
				$unit = '';
				if(!isArrayKeyAnEmptyString($value['durationtype'], $timeoffoptions)){
					$unit = ' on Leave';
				}
				$jsondata[$key]['title'] = $value['user'].$unit;
				$jsondata[$key]['start'] = $value['startdate'];
				$jsondata[$key]['end'] = $value['enddate'];
				if((isTimesheetEmployee() && $value['userid'] == $session->getVar('userid')) || ($acl->checkPermission('Time off', ACTION_APPROVE))){
					// $jsondata[$key]['url'] = $this->view->serverUrl($this->view->baseUrl('timeoff/view/id/'.encode($value['id'])));
				}
			}
		}
	
		// debugMessage($jsondata);
		echo json_encode($jsondata);
	}
}
