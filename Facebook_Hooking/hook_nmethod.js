setTimeout(function(){
	Java.perform(function(){
		Java.enumerateLoadedClasses({
			onMatch: function(className) { 
				
				if (className.indexOf("com.facebook.cryptopub") != -1) {
					
					 var target = Java.use("com.facebook.cryptopub.CryptoPubNative"); 
					 target.encryptNative.overload('int', 'java.lang.String', 'java.lang.String', 'java.lang.String').implementation = function(i1,r1,r2,r3) {
					console.log(i1,r1,r2,r3);

				  return  this.encryptNative.overload('int', 'java.lang.String', 'java.lang.String', 'java.lang.String').call(this,i1, r1,r2,r3);
				};
				}
			},
			onComplete: function() {}
		});
	});
},0);