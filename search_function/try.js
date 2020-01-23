var id_check = require('./id_check.js');
var oku  = require('./oku.js');
var fs  = require('fs');

xFile = process.argv[2];
xSayArt = 0
if (fs.existsSync(xFile)) {
	oku(xFile).split('\n').forEach( function(xas) {
		var query_id = xas.replace('\n','').replace('\r','')
		if (!fs.existsSync("response/"+query_id+".txt")) {
			id_check(query_id).then(function(xdata) {
				console.log(xdata)
				}).catch(function (err) {})
				
		}
				
	});
}

