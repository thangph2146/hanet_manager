<?php
/**
 * 11/2/2022
 * AUTHOR:PDV-PC
 */

namespace App\Models;

use Config\Database;

class My_Model
{
	protected $db;

	/*public function connect($databaseName)
	{
		$this->db = Database::connect($databaseName);
	}
	*/
	public function getWeekScheduledByBuildingID($BuildingID, $TodayDate, $StudentID)
	{
		$sql = "SELECT B.BuildingID, B.BuildingName, R.RoomID
									,PS.WeekScheduleID, PS.ScheduleStudyUnitID
									,PS.PeriodID, PS.Periods, PS.NumberOfPeriods
									,PS.StudyUnitID, PS.StudyUnitName
									,PS.ProfessorID, PS.ProfessorName
									,PS.Date, PS.Status, PS.YearStudy, PS.TermID
									,PS.DayOfWeek, PS.Week, PS.Year
									,PS.DepartmentID, PS.TeachingRoleID, PS.TeachingRoleName
									,PS.ReasonID, PS.ReasonName, PS.CurriculumID
									,SS.StudentID, PE.BeginTime
  				FROM [CoreUis].[dbo].[psc_Dic_Buildings] B
  				INNER JOIN [CoreUis].[dbo].[psc_Rooms] R
    				ON B.BuildingID = R.BuildingID
  				INNER JOIN [CoreUis].[dbo].[vw_psc_Sch_ProfessorWeekSchedules] PS
    				ON PS.[RoomID] = R.[RoomID] AND PS.Date = '" .$TodayDate."' AND PS.Status = 1
  				INNER JOIN [CoreUis].[dbo].[psc_StudentScheduleStudyUnits] SS
    				ON SS.ScheduleStudyUnitID = PS.[ScheduleStudyUnitID] AND SS.StudentID = '" .$StudentID ."'
  				INNER JOIN [CoreUis].[dbo].[psc_Dic_Periods] PE
    				ON PE.PeriodID = PS.PeriodID
  				WHERE B.[BuildingID] = '" . $BuildingID ."'";

		$this->db = Database::connect('CoreUis');


	}
}
