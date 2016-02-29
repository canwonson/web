<?php
namespace Admin\Controller;
use QL\QueryList;
use Admin\Controller\AdminController;

class DatabaseInfoController extends AdminController
{
	public $data = array(
		'name' => 'web_',
		'columns' =>'',
		);

	public function index()
	{
		$Model = new \Think\Model();
		$db_conf='mysql://'.C('DB_USER').':'.C('DB_PWD').'@'.C('DB_HOST').':3306/information_schema';
		$data = $Model->db(1,$db_conf)->table('tables')->where(array('TABLE_SCHEMA'=>C('DB_NAME')))->field('table_name,table_rows,create_time,table_comment')->select();
		$this->assign('data',$data);
		$this->display();
	}

	public function create(){
		$this->assign('data',$this->data);
		$this->display();
	}

	public function store(){
		$table = I('post.name');
		$columns = I('post.columns');
		$sql = 'create table '.$table.' ('.$columns.' );';
		$Model = new \Think\Model();
		$result = $Model->execute($sql);

		if ($result === 0) {
			$info = $table.' 添加成功!';
			$this->assign('data',$this->data);
			$this->assign('success',$info);
			$this->display('create');

		}
	}

	public function show(){
		$table = I('get.table');
		$Model = new \Think\Model();
		$sql = 'show columns from '.$table.';';
		$tabel_info = $Model->query($sql);
		$data = $Model->table($table)->select();
		$this->assign('table',$table);
		$this->assign('data',$data);
		$this->assign('tabel_info',$tabel_info);
		$this->display();
	}

	public function delete(){
		$table = I('get.table');
		$sql = 'drop table '.$table.';';
		$Model = new \Think\Model();
		$result = $Model->execute($sql);
		if ($result === 0) {
			$info = $table.' 删除成功!';
			$this->assign('data',$this->data);
			$this->assign('success',$info);
			$this->redirect('index');

		}
	}
}

?>