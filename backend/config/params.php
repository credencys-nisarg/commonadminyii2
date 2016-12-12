<?php
return [
    
    'adminEmail' => 'admin@example.com',
    'pageSize' 	 => '10',
    'saveDateFormat'=>'Y-m-d H:i:s',
    'displayDateFormat'=>'Y-m-d h:i A',
    'siteUrl'=>'http://'.$_SERVER['HTTP_HOST'].'/commonadminyii2/backend/web/index.php/',
    'siteTimeZone'=>'',//if it is delcared as blank then use client's PC timezone else consider timezone from here
    'userImageUrl'=>'http://'.$_SERVER['HTTP_HOST'].'/commonadminyii2/public/upload/user/',
    'userImageThumbUrl'=>'http://'.$_SERVER['HTTP_HOST'].'/commonadminyii2/public/upload/user/thumb/',
    'logoUploadUrl' => $_SERVER['DOCUMENT_ROOT'].'/commonadminyii2/public/upload/logo/',
    'faviconUploadUrl' => $_SERVER['DOCUMENT_ROOT'].'/commonadminyii2/public/upload/favicon/',
    'imgLogoUrl' => 'http://'.$_SERVER['HTTP_HOST'].'/commonadminyii2/public/upload/logo/',
    'imgfaviconUrl' => 'http://'.$_SERVER['HTTP_HOST'].'/commonadminyii2/public/upload/favicon/',
    
    'languages' => [
      'fr' => 'french',
        'ru' => 'russion',
        'es' => 'Spanish',
        'en' => 'english'
    ],
    
     'imgExtentions' => [
     'jpeg',
     'jpg',
     'gif',
     'png'  
    ],
];
