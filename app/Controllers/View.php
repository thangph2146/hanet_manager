<?php
/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CoreUis\vw_psc_Sch_ProfessorWeekScheduleModel;
use App\Models\Muster\MAttendedStudentModel;

class View extends BaseController {

	public function ScheduleStudyUnit($ScheduleStudyUnitID)
	{
		$schedulesModel = new vw_psc_Sch_ProfessorWeekScheduleModel();

		$DateSchedules = $schedulesModel->getAllDateByScheduleStudyUnitID($ScheduleStudyUnitID);
		helper('array');
		$DateSchedules = array_deep_search('DateRange',$DateSchedules);

		//menu Student
		$StudentScheduleStudyUnits = new MAttendedStudentModel();
		$data = $StudentScheduleStudyUnits->getStudentIDScheduleStudyUnits(current_student()->StudentID,$DateSchedules, $ScheduleStudyUnitID);
		$temparray = [];
		foreach($data as $Student)
		{
			$temparray[] = array_keys($Student);
			$temparray[] = array_values($Student);
		}
		//$data = array_group_by($data, 'YearStudy', 'TermID');
		return view('View/ScheduleStudyUnit', [
			'data' => $temparray
		]);
	}

}
