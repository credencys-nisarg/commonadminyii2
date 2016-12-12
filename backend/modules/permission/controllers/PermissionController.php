<?php

namespace backend\modules\permission\controllers;

use Yii;
use backend\modules\permission\models\Permission;
use backend\modules\permission\models\PermissionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PermissionController implements the CRUD actions for Permission model.
 */
class PermissionController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Permission models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new PermissionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Permission model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Function to get class name
     * @param string $class Class name with php extension
     * @return string Class name
     */
    public function getClassName($class) {
        // Explode file name
        $explodeFile = explode(".", $class);
        return $explodeFile[0];
    }

    /**
     * Function to get class & methods
     * @return array Class methods
     */
    public function scanControllerClass() {
        $classMethods = [];
        // Get directory path
        $dir = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'controllers';
        // Scan the controller class from directroy
        $controllerClasses = scandir($dir, 0);
        foreach ($controllerClasses as $class) {
            if ($class != '.' && $class != '..' && strpos($class, 'SiteController') === false) {
                // Call to get class name
                $className = $this->getClassName($class);
                // Remove controller word from class name
                $originalClassName = str_replace('Controller', '', $className);
                // Call to get class methods
                $classMethods[strtolower($originalClassName)] = $this->getClassMethods($className);
            }
        }
        return $classMethods;
    }

    /**
     * Function to get methods of class
     * @param string $class Class name
     * @return array Class methods
     */
    public function getClassMethods($class) {
        $className = "backend\controllers\\".$class;
        $methodNames = [];
        // Get list of methods
        $methods = get_class_methods($className);
        if (!empty($methods) && count($methods) > 0) {
            foreach ($methods as $method) {
                if(strpos($method, 'action') !== false && $method != 'actions') {
                    $method = str_replace('action', '', $method);
                    $methodNames[] = strtolower($method);
                }
            }
        }
        return $methodNames;
    }

    /**
     * Creates a new Permission model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        // Call to scan controller class
        $classMethods = $this->scanControllerClass();
        
        $model = new Permission();

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            
            // If action array not empty
            if(isset($post['action']) && count($post['action']) > 0){
                // multi insert raw
                $roleId = $post['Permission']['roleid'];
                $userId = $post['Permission']['userid'];
                $tableName = 'permission';
                $columnNameArray = ['roleid','userid','action_name','class_name'];
                $bulkInsertArray = [];
                foreach ($post['action'] as $key => $val) {
                    $bulkInsertArray[] = [$roleId,$userId,implode(",", $val),$key];
                }
                // below line insert all your record and return number of rows inserted
                $insertCount = Yii::$app->db->createCommand()
                        ->batchInsert($tableName, $columnNameArray, $bulkInsertArray)
                        ->execute();
                if($insertCount){
                    Yii::$app->session->setFlash('success','User permission created.');
                    return $this->redirect(['index']);
                }else{
                    Yii::$app->session->setFlash('error','Some error occur...please try again.');
                }
            }else{
                Yii::$app->session->setFlash('error','Please select class ations');
            }
        } else {
            return $this->render('create', [
                        'model' => $model, 'classMethods' => $classMethods, 'userPermission' => []
            ]);
        }
    }

    /**
     * Updates an existing Permission model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        // Call to scan controller class
        $classMethods = $this->scanControllerClass();
        
        $model = $this->findModel($id);
        // Call to get user permission
        $userPermission = $this->getUserPermission($model->userid);

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            
            // If action array not empty
            if(isset($post['action']) && count($post['action']) > 0){
                // multi insert raw
                $roleId = $post['Permission']['roleid'];
                $userId = $post['Permission']['userid'];
                $tableName = 'permission';
                $userPermissionId = [0];
                foreach ($post['action'] as $key => $val) {
                    if (($row = Permission::findOne(['roleid' => $roleId, 'userid' => $userId, 'class_name'=> $key])) !== null) {
                        $userPermissionId[] = $row->permission_id;
                        // Update class permission
                        Yii::$app->db->createCommand()->update($tableName, ['action_name' => implode(",", $val)], "roleid = $roleId AND userid = $userId AND class_name = '$key'")->execute();
                    }else{
                        // insert new permission
                        Yii::$app->db->createCommand()->insert($tableName, ['roleid' => $roleId,'userid' => $userId,'action_name' => implode(",", $val),'class_name' => $key])->execute();
                        $userPermissionId[] = Yii::$app->db->getLastInsertID();
                    }
                }
                // Delete user permission
                Yii::$app->db->createCommand("DELETE FROM $tableName WHERE roleid = $roleId AND userid = $userId AND permission_id NOT IN (" . implode(",", $userPermissionId) . ")")->execute();
                
                Yii::$app->session->setFlash('success','User permission updated.');
                return $this->redirect(['index']);
            }else{
                Yii::$app->session->setFlash('error','Please select class ations');
            }
        } else {
            return $this->render('update', [
                        'model' => $model, 'classMethods' => $classMethods, 'userPermission' => $userPermission
            ]);
        }
    }
    
    /**
     * Function to get user action permissions
     * @param int $userId User id
     * @return array User permission array
     */
    public function getUserPermission($userId) {
        $classMethods = [];
        $methods = Permission::find()->where(['userid' => $userId])->all();
        foreach ($methods as $method) {
            $classMethods[$method->class_name] = explode(",", $method->action_name);
        }
        return $classMethods;
    }
    
    /**
     * Deletes an existing Permission model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Permission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Permission the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Permission::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
