<?php
function convertDateTimeToCustom($conn,$date,$time,$scheduleName,$scheduleDays){
	if($results=$conn->query("SELECT * FROM days WHERE scheduleName=\"".$scheduleName."\" AND `date`=\"$date\"")){
		while ($row = $results->fetch_assoc()) {
			//mktime(16,0,0,1,0,1970)=0
			return floor($row["day"]/$scheduleDays)*86400000+mktime(explode(":",$time)[0]+16,explode(":",$time)[1],0,1,0,1970);
		}
		return -1;
	} else {
		return -1;
	}
}
function convertCustomTimeToUnix($conn,$customTime,$scheduleName,$scheduleDay,$scheduleDays){
	$day=(($customTime-($customTime%86400000))/86400000)*$scheduleDays-($scheduleDays-$scheduleDay);
	$time=$customTime%86400000;
	if($results=$conn->query("SELECT * FROM days WHERE scheduleName=\"".$scheduleName."\" AND `day`=$day")){
		while ($row = $results->fetch_assoc()) {
			return $time+mktime(0,0,0,explode("-", $row["date"])[1],explode("-", $row["date"])[2],explode("-", $row["date"])[0]);
		}
		return -1;
	} else {
		return -1;
	}
}