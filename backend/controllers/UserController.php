<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\Countries;
use common\models\States;
use common\models\Cities;
use common\models\UserLog;
use common\models\UserSearch;
use yii\web\Controller; 
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\helper\AppHelper;
use yii\helpers\Url;
use yii\imagine\Image;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $countries = Countries::find()->all();
        $states = States::find()->all();
        $cities = Cities::find()->all();
        
        if (Yii::$app->request->isAjax && $model->load($_POST)){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }    
         /* Nisarg Patel 
        12/10/16
        Start  : To set(store) login password with hash security */
        $valuesPosted = Yii::$app->request->post();
        $valuesPosted = $this->setFieldValues($valuesPosted, $model, "fromCreate");
        /* End  : To store uploaded profile image */
        if ($model->load($valuesPosted) && $model->save()) {
        /* End Nisarg Patel */
                    return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'countries' => $countries,
                'states' => $states,
                'cities' => $cities,
            ]);
        }
    }
    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
       
        $countries = Countries::find()->all();
        $states = States::find()->all();
         $cities = Cities::find()->all();
        
        if (Yii::$app->request->isAjax && $model->load($_POST)){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
         /* Nisarg Patel 
        12/10/16
        Start  : To set(store) login password with hash security */
        $valuesPosted = Yii::$app->request->post();
        $valuesPosted = $this->setFieldValues($valuesPosted, $model, "fromUpdate");
       
        /* End  : To store uploaded profile image */
        if ($model->load($valuesPosted) && $model->save()) {
        /* End Nisarg Patel */
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'countries' => $countries,
                'states' => $states,
                'cities' => $cities,
            ]);
        }
    }

    /**
     * Nisarg Patel 12/10/16
     * Uploads user profile image from dropzone
     * Parameters :
     * valuesPosted : posted values from form
     * model : Existing model object
     * fromAction : To identify from which action function is called
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function setFieldValues($valuesPosted, $model, $fromAction)
    {
        if(isset($valuesPosted['User']['password']))
        {
            if($model->password != $valuesPosted['User']['password'])
            {
                $valuesPosted['User']['password'] = Yii::$app->getSecurity()->generatePasswordHash($valuesPosted['User']['password']);        
            }
        }
        /* Start  : To store uploaded profile image */
        if(isset($valuesPosted['User']))
        {
            $setDefaultImage = 0;
            $session = Yii::$app->session;
            if ($session->has('userProfileImage'))
            {             
                $userProfileImage = $session->get('userProfileImage');             
                if($userProfileImage != "")
                {
                    $valuesPosted['User']['user_image'] = $userProfileImage;
                    $session->remove('userProfileImage');
                }
                else
                {
                    $setDefaultImage = ($fromAction == "fromCreate" ? 1 : 0);
                }
            }
            else
            {
                $setDefaultImage = ($fromAction == "fromCreate" ? 1 : 0);                
            }            
            if($setDefaultImage == 1)
            {
                $valuesPosted['User']['user_image'] = "default.jpeg";
            }
        }
        return $valuesPosted;
    }
    /**
     * Nisarg Patel 12/10/16
     * Uploads user profile image from dropzone
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProfileImageUpload()
    {
        $fileName = 'file';
        $uploadPath = realpath(Yii::$app->basePath. '/../public/upload/user/tmp/') ;
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        if (isset($_FILES[$fileName])) {
            $file = \yii\web\UploadedFile::getInstanceByName($fileName);            
            if ($file->saveAs($uploadPath . '/' . $file->name)) {
                $session = Yii::$app->session;
                $session->set('userProfileImage', $file->name);
                //Now save file data to database
                echo \yii\helpers\Json::encode($file);
            }
        }
        return false;
    }

     /*
     * ChangePassword 
     * params in post : oldpassword,newpassword,confirmpassword
     */
    public function actionChangepassword() {
        $userData = User::findOne(Yii::$app->user->identity->id);

        /* $oldPassword = $userData->password;//$userData['password']; */

        $model = new User();
        $model->scenario = 'changepassword';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $enteredoldPassword = $_POST['User']['old_password'];
            $newPassword = $_POST['User']['new_password'];

            //check password..
            //if ($userData->password == $enteredoldPassword) {
            if ($userData->validatePassword($enteredoldPassword)) {                
                //$userData->password = $newPassword;
                $userData->password = Yii::$app->getSecurity()->generatePasswordHash($newPassword);
                /* $userData->update(); */

                if ($userData->validate()) {

                    //validate with try catch
                    try {

                        if ($userData->update()) {
                            Yii::$app->getSession()->setFlash(
                                    'success', 'Password changed successfully..!! '
                            );
                            return $this->redirect(['index']);
                        } else {
                            Yii::$app->getSession()->setFlash(
                                    'error', 'Password not changed..!!'
                            );
                            return $this->redirect(['index']);
                        }
                    } catch (Exception $e) {

                        Yii::$app->getSession()->setFlash(
                                'error', "{$e->getMessage()}"
                        );
                        return $this->render('changepassword', [
                                    'model' => $model
                        ]);
                    }
                }
            } else {
                // wrong password
                Yii::$app->session->setFlash('success', "Old Password Incurrect..!!");

                return $this->render('changepassword', [
                            'model' => $model,
                ]);
            }
        }
        return $this->render('changepassword', [
                    'model' => $model,
        ]);
    }

    /**
     * @Desc Crop Profile Image and also create its thumb
     * @return ThumbImage Name
     */
    public function actionCropImage(){
        $request = Yii::$app->request;
        $image_source = realpath(Yii::$app->basePath. '/../public/upload/user/tmp/')."/".$request->post('imageName');
        $new_source = realpath(Yii::$app->basePath. '/../public/upload/user/');
        $dataAry = array(
            'width' => $request->post('image_width'),
            'height' => $request->post('image_height'),
            'x' => $request->post('x'),
            'y' => $request->post('y'),
            'w' => $request->post('w'),
            'h' => $request->post('h'),
            'imgpath' => $image_source,
            'newpath'=>$new_source,
            'image'=>$request->post('imageName'),
        );
        $cropedImage = AppHelper::CropImage($dataAry);
        $thumbnail = $new_source."/thumb/".$request->post('imageName');
        Image::thumbnail( $cropedImage , 50, 50)
            ->save($thumbnail, ['quality' => 50]);
        return $thumbnail;
    }
    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if($id!=Yii::$app->user->id){
            $this->findModel($id)->delete();    
        }else{
            Yii::$app->getSession()->setFlash('error', 'You can not delete your own user itself.');
        }
        return $this->redirect(['index']);
    }
    
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
        @click on profile and view profile page and u also update into it
        @param : $id its came into the $userdetails of user_id
        return login user details.
    */
    public function actionShowUserProfile()
    {
        /*
            @use user identify for identify user id display it details.
        */
        $userDetails = User::findOne(Yii::$app->user->identity->id);

        $id = $userDetails->user_id;
        
        $model = $this->findModel($id);
        
        $countries = Countries::find()->all();
        $states = States::find()->all();
         $cities = Cities::find()->all();
        
        if (Yii::$app->request->isAjax && $model->load($_POST)){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
         /* Nisarg Patel 
        12/10/16
        Start  : To set(store) login password with hash security */
        $valuesPosted = Yii::$app->request->post();
        $valuesPosted = $this->setFieldValues($valuesPosted, $model, "fromUpdate");
       
        /* End  : To store uploaded profile image */
        if ($model->load($valuesPosted) && $model->save()) {
        /* End Nisarg Patel */
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'countries' => $countries,
                'states' => $states,
                'cities' => $cities,
            ]);
        }
    }

    /*
        @function for user dependent drop down select country and return state of the countries
        @param: $id it set when selected country 
        return : it return all states of this countries
    */
    public function actionStateLists($id)
    {
        
        /*$stateCount = States::find()
                        ->where(['country_id'=> $id])
                        ->count();*/

        $stateListing = States::find()
                        ->where(['country_id'=> $id])
                        ->all();

       if(count($stateListing) > 0){

            foreach ($stateListing as $stateListing) {
                # code...       
                echo "<option value = '" . $stateListing->state_id ."'>" . $stateListing->state_name . "</option>";
            }

       }else{
            echo "<option>Select State</option>";
       }
    }

    /*
        @create function cityList for get list of all state if country selected 
        
    */
    public function actionCityLists($id)
    {   
       
        /*$citycount = Cities::find()
                        ->where(['state_id'=> $id])
                        ->count();*/
        
        $cityListing = Cities::find()
                        ->where(['state_id'=> $id])
                        ->all();                       
    
       if(count($cityListing) > 0){
            $cityStr = "";
            foreach ($cityListing as $cityListing) {
                # code...
                $cityStr .=  "<option value = '" . $cityListing->city_id ."'>" . $cityListing->city_name . "</option>";
            }
            echo $cityStr;
            //exit;

       }else{
            echo "<option>No city Here</option>";
       }
    }
}