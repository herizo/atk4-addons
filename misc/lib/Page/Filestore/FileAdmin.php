<?
class Page_Filestore_FileAdmin extends Page_EntityManager {
	public $controller='Controller_Filestore_File';
	function init(){
		parent::init();
		$this->api->stickyGET('tab');
		switch($_GET['tab']){
			case null:
			case 'file':
				$this->controller='Controller_Filestore_File';
				break;
			case 'volume':
				$this->controller='Controller_Filestore_Volume';
				break;
			case 'type':
				$this->controller='Controller_Filestore_Type';
				break;
		}
	}
	function initMainPage(){
		/*
		$g=$this->add('MVCGrid');
		$c=$g->add('Controller_Filestore_File');
		$g->setController($c);

		*/



		$v=$this->add('View_Columns');
		$g=$v->addColumn()->add('MVCGrid');// volumes

		$c=$g->add('Controller_Filestore_Volume');
		$c->setActualFields(array('name','dirname','stored_files_cnt','enabled'));
		$g->setController($c);
		$g->addColumnPlain('expander_widget', 'editvolume', $this->read_only?'view':'Edit');
		$g->addButton('Add')->js('click')->univ()->dialogURL('Add new',
				$this->api->getDestinationURL('./editvolume'));
		$g->addColumnPlain('confirm','deletevolume','Delete');
		if($_GET['deletevolume']){
			$c->loadData($_GET['deletevolume']);
			$c->delete();
			$g->js(null,$g->js()->univ()->successMessage('Record deleted'))->reload()->execute();
		}
		if($_GET['edit']){
			$this->js()->univ()->location($this->api->getDestinationURL($this->returnpage,
						Array('id' => $_GET['edit'])))->execute();
		}


		$g=$v->addColumn()->add('MVCGrid');// types

		$c=$g->add('Controller_Filestore_Type');
		$c->setActualFields(array('name','mime_type','extends'));
		$g->setController($c);
		$g->addColumnPlain('expander_widget', 'edittype', 'Edit');
		$g->addButton('Add')->js('click')->univ()->dialogURL('Add new',$this->api->getDestinationURL('./edittype'));
		$g->addColumnPlain('confirm','deletetype','Delete');
		if($_GET['deletetype']){
			$c->loadData($_GET['deletetype']);
			$c->delete();
			$g->js(null,$g->js()->univ()->successMessage('Record deleted'))->reload()->execute();
		}
		if($_GET['edit']){
			$this->js()->univ()->location($this->api->getDestinationURL($this->returnpage,
						Array('id' => $_GET['edit'])))->execute();
		}


		$_GET['tab']='';$this->api->stickyGET('tab');

		$g=$this->add('MVCGrid','grid');

		$c=$g->add('Controller_Filestore_File');
		
		if($this->grid_actual_fields)
			$c->setActualFields($this->grid_actual_fields);

		$g->setController($c);

		if($this->allow_edit)
			$g->addColumnPlain('expander_widget', 'edit', $this->read_only?'view':'edit');
		if($this->allow_add){
			$g->addButton('Add')->js('click')->univ()->dialogURL('Add new',$this->api->getDestinationURL('./edit'));
		}
		if($this->allow_delete){
			$g->addColumnPlain('confirm','delete');
			if($_GET['delete']){
				$c->loadData($_GET['delete']);
				$c->delete();
				$g->js(null,$g->js()->univ()->successMessage('Record deleted'))->reload()->execute();
			}
		}
		
		
		if($this->allow_edit){
			if($_GET['edit']){
				$this->js()->univ()->location($this->api->getDestinationURL($this->returnpage,
							Array('id' => $_GET['edit'])))->execute();
			}
		}



	}
	function page_editvolume(){
		$this->c=($this->add($this->controller='Controller_Filestore_Volume'));
		return parent::page_edit();
	}
	function page_edittype(){
		$this->c=($this->add($this->controller='Controller_Filestore_Type'));
		return parent::page_edit();
	}
}