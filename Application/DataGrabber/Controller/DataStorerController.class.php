<?php
namespace DataGrabber\Controller;

class DataStorerController extends Controller
{
	$where['good_name'] = $data['good_name'];
	$count = $Good->where($where)->count();
	if(!$count){
		$Good->add($data);
	}
}


?>