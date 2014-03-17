var BASE_URL = 'http://192.168.0.136/ci/api/my_interface/';
var restConfig = {'Authorization': 'Basic c3F0OllXYVdNVEl6TkE='};

function getApiUrl(string){
	return BASE_URL + string;
}