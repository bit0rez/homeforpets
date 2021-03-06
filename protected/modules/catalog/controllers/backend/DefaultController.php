<?php

class DefaultController extends BackEndController
{
	public function actions()
	{
		return array(
			'move' => 'ext.SSortable.SSortableAction',
		);
	}
    
	/*public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}*/

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	/*public function actionCreate($id)
	{
		$model=new CatalogCategory;
		$model->parent_id=$id;
		$this->breadcrumbs['Каталог']=array('/catalog');
		$this->breadcrumbs[]='Добавление категории';


		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CatalogCategory']))
		{
			$model->attributes=$_POST['CatalogCategory'];

			if($model->save()) {
				$this->redirect(array('index','id'=>$model->parent_id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}*/

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{

		$model = $this->loadModel($id);

		$this->breadcrumbs=CatalogCategory::getParents($model->id);
		$this->breadcrumbs[]=$model->title;
		$this->breadcrumbs[]='Редактирование';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CatalogCategory']))
		{
			$model->attributes=$_POST['CatalogCategory'];

			if($model->save()) {
				$this->redirect(array('index','id'=>$model->parent_id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Вывод категории (подкатегории и товары)
	 */
	public function actionIndex($id = 0)
	{
        if (!$category = $this->loadModel($id))
		{
            $category  = new CatalogCategory();
            $category->id = 0;
            $category->title = 'Каталог животных';
        }
		$this->breadcrumbs = CatalogCategory::getParents($category->id);
		$this->breadcrumbs[] = $category->title;


        $category_criteria=new CDbCriteria;

		$category_criteria->compare('parent_id',$category->id);

		$categoryDataProvider=new CActiveDataProvider('CatalogCategory', array(
			'criteria'=>$category_criteria,
			'sort' => array(
				'defaultOrder' => 'sort_order ASC',
				),
			'pagination'=>false,
		));

        $products=new CatalogProduct('search');
        $products->unsetAttributes();

        $products->id_category=$category->id;
        $products->attach = 0;

		if(isset($_GET['CatalogProduct']))
			$products->attributes=$_GET['CatalogProduct'];

		$this->render('index',array(
            'products'=>$products,
            'categoryDataProvider'=>$categoryDataProvider,
			'category'=>$category,
		));
	}

	/**
	 * Загружаем модель категории
	 */
	public function loadModel($id)
	{
		$model=CatalogCategory::model()->findByPk($id);
		/*if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');*/
		return $model;
	}
}