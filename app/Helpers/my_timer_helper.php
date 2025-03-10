<?php
/**
 * 10/27/2022
 * AUTHOR:PDV-PC
 */
if (!function_exists('getPeriodID')) {

	function getPeriodID()
	{
		$i = null;
		if (time() >= strtotime(service('settings')->get('App.StartPeriod_1'))
			&& time() <= strtotime(service('settings')->get('App.EndPeriod_1'))){
			$i = 1;
		}
		else if (time() >= strtotime(service('settings')->get('App.StartPeriod_6'))
			&& time() <= strtotime(service('settings')->get('App.EndPeriod_6')))
		{
			$i = 6;
		}
		else if (time() >= strtotime(service('settings')->get('App.StartPeriod_11'))
			&& time() <= strtotime(service('settings')->get('App.EndPeriod_11')))
		{
			$i = 11;
		}
		return $i;
	}
}
