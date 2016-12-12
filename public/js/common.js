$(function(){
	/**
	* @used for set client timezone after login successful using ajax and set it to session
	*/
	if(typeof(isLogin) !== 'undefined' && isLogin){
			app.config.isLogin = isLogin;
			app.config.siteUrl = siteUrl;
			
			var tz = jstz.determine();
    		var timezonename = tz.name();
			
			var url = 'site/setclienttimezone';

			var fromData = {'timezonename': timezonename};
			app.config.ajaxRequest(
	            {
	                type: "post",
	                url: app.config.siteUrl + url,
	                data: fromData,
	                cache: false,
	                //contentType: false,
	                dataType: 'json',
	                processData: true,
	            },
	            function (data) {
	                console.log(data.success);
	            },
	            function (err) {
	                console.log(err);
	            }
	        );
	        return false;
	}
});