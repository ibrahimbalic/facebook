var request = require("request-promise");
var fs  = require('fs');
module.exports = async function(query_id) {
    var options = {
        uri: 'https://b-graph.facebook.com/graphqlbatch',
        method: "POST",
		body: 'method=GET&locale=tr_TR&pretty=false&format=json&queries=%7B%22o0%22%3A%7B%22doc_id%22%3A%22'+query_id+'%22%2C%22query_params%22%3A%7B%220%22%3A%22%2B905335838219%22%7D%7D%7D&fb_api_req_friendly_name=NativeTemplateSearchQuery&fb_api_caller_class=graphservice&fb_api_analytics_tags=%5B%22GraphServices%22%5D&server_timestamps=true&access_token=350685531728%7C62f8ce9f74b12f84c123cc23437a4a32',
		timeout: 60000,
		headers: {"Content-Type": "application/x-www-form-urlencoded","X-FB-Friendly-Name": "NativeTemplateSearchQuery","Host": "b-graph.facebook.com","User-Agent": "[FBAN/FB4A;FBAV/251.0.0.31.111;FBBV/188827992;FBDM/{density=2.625,width=1080,height=1920};FBLC/tr_TR;FBRV/0;FBCR/Android;FBMF/unknown;FBBD/generic_x86;FBPN/com.facebook.katana;FBDV/Android SDK built for x86;FBSV/4.4.2;FBOP/1;FBCA/x86:unknown;]","X-FB-Net-HNI": "310260","X-FB-SIM-HNI": "310260","X-FB-Connection-Type": "mobile.LTE","X-FB-HTTP-Engine": "Liger","IbrahimBalicTest": "RateLimitTest"}
    }

    try {
        var result = await request(options);
	
			fs.writeFile("response/"+query_id+".txt", result, 'utf8', function(err, result) {
			if(err) console.log('error', err);
			}); 
			
        return result;
    } catch (errx) {
       
			fs.writeFile("response/"+query_id+".txt", errx, 'utf8', function(err, result) {
			if(err) console.log('error', err);
			}); 
			
		
    }
};
