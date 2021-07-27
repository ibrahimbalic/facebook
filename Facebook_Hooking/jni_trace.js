// http://www.cxyzjd.com/article/qq314000558/109448475
function TraceJni(){
    Java.perform(function(){

        var pSize = Process.pointerSize
        var env = Java.vm.getEnv()
		// degiskeni tanimla
		var jclassAddress2NameMap = {};
	
        //https://docs.oracle.com/javase/8/docs/technotes/guides/jni/spec/functions.html#NewStringUTF
        var GetStaticMethodID = 113,findclass = 6,RegisterNatives = 215;
    
        function getNativeAddress(idx) {
            return env.handle.readPointer().add(idx * pSize).readPointer()
        }

        Interceptor.attach(getNativeAddress(findclass),{
            onEnter:function(args){
                console.error("-------------findClass-------------")
                console.warn("env\t--->\t"+args[0])
                console.warn("class\t--->\t"+args[1].readCString())
				// hafizaya al - fixledigim alan
				jclassAddress2NameMap[args[0]] = args[1].readCString();
            },
            onLeave:function(retval){}
        })

        Interceptor.attach(getNativeAddress(GetStaticMethodID),{
            onEnter:function(args){
                console.error("\n-------------GetStaticMethodID-------------")
                console.warn(args[0])
                console.warn(args[1])
                console.warn(args[2].readCString())
            },
            onLeave:function(retval){}
        })

        //RegisterNative结构体参照：
        //https://android.googlesource.com/platform/libnativehelper/+/master/include_jni/jni.h#129
        Interceptor.attach(getNativeAddress(RegisterNatives), {
            onEnter: function(args) {
                console.log(parseInt(args[3]))
				
                for (var i = 0,nMethods = parseInt(args[3]); i < nMethods; i++) {
                    var structSize = pSize * 3; // = sizeof(JNINativeMethod)
                    var methodsPtr = ptr(args[2]);
                    var signature = methodsPtr.add(i * structSize + pSize).readPointer();
                    var fnPtr = methodsPtr.add(i * structSize + (pSize * 2)).readPointer(); // void* fnPtr
                    var jClass = jclassAddress2NameMap[args[0]].split('/');
                    var methodName = methodsPtr.add(i * structSize).readPointer().readCString();
                    console.log('\x1b[3' + '6;01' + 'm', JSON.stringify({
                        module: DebugSymbol.fromAddress(fnPtr)['moduleName'],
                        // https://www.frida.re/docs/javascript-api/#debugsymbol
                        package: jClass.slice(0, -1).join('.'),
                        class: jClass[jClass.length - 1],
                        method: methodName,
                        // methodsPtr.readPointer().readCString(), // char* name
                        signature: signature.readCString(),
                        // char* signature TODO Java bytecode signature parser { Z: 'boolean', B: 'byte', C: 'char', S: 'short', I: 'int', J: 'long', F: 'float', D: 'double', L: 'fully-qualified-class;', '[': 'array' } https://github.com/skylot/jadx/blob/master/jadx-core/src/main/java/jadx/core/dex/nodes/parser/SignatureParser.java
                        address: fnPtr
                    }), '\x1b[39;49;00m');
                }
            }
        });
    })
}
Java.perform(TraceJni);