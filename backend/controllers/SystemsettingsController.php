<?php
/**
 * SystemsettingsController Class 
 *
 * This class is used for getting SystemDetails and Site configurations 
 * in whole application
 *
 * @copyright  2016 Credencys
 * @version    Release: 1.0
 * @since      Class available since Release 1.0.0
 * @author     Kinjal Shah
 */ 

namespace backend\controllers;
use Yii;
use common\models\Systemsettings;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class SystemsettingsController extends Controller
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
    * Lists all Systemsettings model variables for creating custom form.
    * @author Kinjal Shah
    */ 
    public function actionIndex()
    {
        $model = Systemsettings::find()->all();
        
        return $this->render('index', [
            'model' => $model,
        ]);
    }
    
    /**
     * Updates an existing Systemsettings model.
     * 
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @author Kinjal Shah
     */
    public function actionUpdate()
    {
           // for getting all Settings Record.
           $model = new Systemsettings();
           $model = Systemsettings::find()->all();
           
           // for storing logo and favicon image in variable.
           foreach($model as $mimg)
            {
                if($mimg['name'] == 'logo')  
                  $logoimg =  $mimg['val'];
                if($mimg['name'] == 'favicon')
                   $faviconimg = $mimg['val'];
            }
            //if data is posted for update settings.
            if(Yii::$app->request->post())
            {   
              $valuesPosted = Yii::$app->request->post();
              unset($valuesPosted['_csrf-backend']);
              unset($valuesPosted['submit']);
        
                if(isset($_FILES['logo']['name']) && $_FILES['logo']['name'] != '')
                {
                    
                    // get Extention
                    $extarr = explode(".",$_FILES['logo']['name']);
                    $ext = $extarr[1];
                  
                    // get all Extention
                    $logoExt = \Yii::$app->params['imgExtentions'];  
                    if (in_array($ext, $logoExt))
                    {
                         /* image upload code here.*/
                        // get upload url
                        $logoImage =  \Yii::$app->params['logoUploadUrl'];
                        // get upload file if exist.
                        $uploadlogofile = $logoImage . $_FILES['logo']['name'];
                        // get file old filename with path for delete it. 
                        $deletelogofile =  $logoImage.$logoimg;
                                               
                        if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadlogofile)) 
                         {  
                            
                            $valuesPosted['logo'] = $_FILES['logo']['name'];
                                 @unlink($deletelogofile);
                            $logoimg  = $_FILES['logo']['name'];    
                         }  
                         else
                             $valuesPosted['logo'] = $logoimg;   
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'Please Enter jpeg, jpg, gif and png images for Logo Image.');
                    }   
                }
                if(isset($_FILES['favicon']['name']) && $_FILES['favicon']['name'] != '')
                {
                    // get Extention
                    $extarr = explode(".",$_FILES['favicon']['name']);
                    $ext = $extarr[1];

                    // get all Extention
                    $faviconExt = \Yii::$app->params['imgExtentions'];  
                    
                    if (in_array($ext, $faviconExt))
                    {
                         // image upload code here.
                         $faviconImage =  \Yii::$app->params['faviconUploadUrl'];
                         $uploadfaviconfile = $faviconImage . $_FILES['favicon']['name'];
                         $deletefaviconfile =  $faviconImage.$faviconimg;
                          
                         if (move_uploaded_file($_FILES['favicon']['tmp_name'], $uploadfaviconfile)) 
                         {  
                                $valuesPosted['favicon'] = $_FILES['favicon']['name'];  
                                    @unlink($deletefaviconfile);
                                $faviconimg = $_FILES['favicon']['name']; 
                         } 
                         else
                             $valuesPosted['favicon'] = $faviconimg;   
                    }
                    else
                    {
                          Yii::$app->session->setFlash('error', 'Please Enter jpeg, jpg, gif and png images for favicon.');
                    }
                }
               
                foreach ($valuesPosted as $key => $value) {
                Yii::$app->db->createCommand("UPDATE systemsettings set val = '".$value."' where name = '".$key."'")->execute();
              } 
              
               $model = Systemsettings::find()->all();
                return $this->render('index', [
                      'model' => $model,
                  ]);
              
            }
            else
            {
               return $this->render('index', [
                          'model' => $model,
                      ]);
                
            }
           
    }       
}
