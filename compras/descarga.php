<?php
	if(isset($_GET['request']) && isset($_GET['file'])){
		$path = "../adodb/adodb.inc.php";
		include ("../admin/var.php");
		include ("../conexion.php");

		$sql="SELECT img_full FROM ic_catalogo WHERE md5(id_item)='".$_GET['request']."' ";
		$result=$db->Execute($sql);
		
		if(!$result->EOF){
			list($link_libro)=$result->fields;
			
			$valicacion = md5(sha1(date('dd/mm/YYYY')));
			
			if($_GET['file']==$valicacion){
				
				if(!file_exists("../".$link_libro)){
					header("Location: ../index.php");	
				}
				
				$mime['3dm'] = "x-world/x-3dmf";
				$mime['3dmf'] = "x-world/x-3dmf";
				$mime['a'] = "application/octet-stream";
				$mime['aab'] = "application/x-authorware-bin";
				$mime['aam'] = "application/x-authorware-map";
				$mime['aas'] = "application/x-authorware-seg";
				$mime['abc'] = "text/vnd.abc";
				$mime['acgi'] = "text/html";
				$mime['afl'] = "video/animaflex";
				$mime['ai'] = "application/postscript";
				$mime['aif'] = "audio/aiff";
				$mime['aif'] = "audio/x-aiff";
				$mime['aifc'] = "audio/aiff";
				$mime['aifc'] = "audio/x-aiff";
				$mime['aiff'] = "audio/aiff";
				$mime['aiff'] = "audio/x-aiff";
				$mime['aim'] = "application/x-aim";
				$mime['aip'] = "text/x-audiosoft-intra";
				$mime['ani'] = "application/x-navi-animation";
				$mime['aos'] = "application/x-nokia-9000-communicator-add-on-software";
				$mime['aps'] = "application/mime";
				$mime['arc'] = "application/octet-stream";
				$mime['arj'] = "application/arj";
				$mime['arj'] = "application/octet-stream";
				$mime['art'] = "image/x-jg";
				$mime['asf'] = "video/x-ms-asf";
				$mime['asm'] = "text/x-asm";
				$mime['asp'] = "text/asp";
				$mime['asx'] = "application/x-mplayer2";
				$mime['asx'] = "video/x-ms-asf";
				$mime['asx'] = "video/x-ms-asf-plugin";
				$mime['au'] = "audio/basic";
				$mime['au'] = "audio/x-au";
				$mime['avi'] = "application/x-troff-msvideo";
				$mime['avi'] = "video/avi";
				$mime['avi'] = "video/msvideo";
				$mime['avi'] = "video/x-msvideo";
				$mime['avs'] = "video/avs-video";
				$mime['bcpio'] = "application/x-bcpio";
				$mime['bin'] = "application/mac-binary";
				$mime['bin'] = "application/macbinary";
				$mime['bin'] = "application/octet-stream";
				$mime['bin'] = "application/x-binary";
				$mime['bin'] = "application/x-macbinary";
				$mime['bm'] = "image/bmp";
				$mime['bmp'] = "image/bmp";
				$mime['bmp'] = "image/x-windows-bmp";
				$mime['boo'] = "application/book";
				$mime['book'] = "application/book";
				$mime['boz'] = "application/x-bzip2";
				$mime['bsh'] = "application/x-bsh";
				$mime['bz'] = "application/x-bzip";
				$mime['bz2'] = "application/x-bzip2";
				$mime['c'] = "text/plain";
				$mime['c'] = "text/x-c";
				$mime['c++'] = "text/plain";
				$mime['cc'] = "text/plain";
				$mime['cc'] = "text/x-c";
				$mime['ccad'] = "application/clariscad";
				$mime['cco'] = "application/x-cocoa";
				$mime['cdf'] = "application/cdf";
				$mime['cdf'] = "application/x-cdf";
				$mime['cdf'] = "application/x-netcdf";
				$mime['cer'] = "application/pkix-cert";
				$mime['cer'] = "application/x-x509-ca-cert";
				$mime['cha'] = "application/x-chat";
				$mime['chat'] = "application/x-chat";
				$mime['class'] = "application/java";
				$mime['class'] = "application/java-byte-code";
				$mime['class'] = "application/x-java-class";
				$mime['com'] = "application/octet-stream";
				$mime['com'] = "text/plain";
				$mime['conf'] = "text/plain";
				$mime['cpio'] = "application/x-cpio";
				$mime['cpp'] = "text/x-c";
				$mime['cpt'] = "application/mac-compactpro";
				$mime['cpt'] = "application/x-compactpro";
				$mime['cpt'] = "application/x-cpt";
				$mime['crl'] = "application/pkcs-crl";
				$mime['crl'] = "application/pkix-crl";
				$mime['crt'] = "application/pkix-cert";
				$mime['crt'] = "application/x-x509-ca-cert";
				$mime['crt'] = "application/x-x509-user-cert";
				$mime['csh'] = "application/x-csh";
				$mime['css'] = "application/x-pointplus";
				$mime['css'] = "text/css";
				$mime['cxx'] = "text/plain";
				$mime['dcr'] = "application/x-director";
				$mime['deepv'] = "application/x-deepv";
				$mime['def'] = "text/plain";
				$mime['der'] = "application/x-x509-ca-cert";
				$mime['dif'] = "video/x-dv";
				$mime['dir'] = "application/x-director";
				$mime['dl'] = "video/dl";
				$mime['dl'] = "video/x-dl";
				$mime['doc'] = "application/msword";
				$mime['dot'] = "application/msword";
				$mime['dp'] = "application/commonground";
				$mime['drw'] = "application/drafting";
				$mime['dump'] = "application/octet-stream";
				$mime['dv'] = "video/x-dv";
				$mime['dvi'] = "application/x-dvi";
				$mime['dwf'] = "drawing/x-dwf (old)";
				$mime['dwf'] = "model/vnd.dwf";
				$mime['dwg'] = "application/acad";
				$mime['dwg'] = "image/vnd.dwg";
				$mime['dwg'] = "image/x-dwg";
				$mime['dxf'] = "application/dxf";
				$mime['dxf'] = "image/vnd.dwg";
				$mime['dxf'] = "image/x-dwg";
				$mime['dxr'] = "application/x-director";
				$mime['elc'] = "application/x-elc";
				$mime['env'] = "application/x-envoy";
				$mime['eps'] = "application/postscript";
				$mime['es'] = "application/x-esrehber";
				$mime['etx'] = "text/x-setext";
				$mime['evy'] = "application/envoy";
				$mime['evy'] = "application/x-envoy";
				$mime['exe'] = "application/octet-stream";
				$mime['f'] = "text/plain";
				$mime['f'] = "text/x-fortran";
				$mime['f77'] = "text/x-fortran";
				$mime['f90'] = "text/plain";
				$mime['f90'] = "text/x-fortran";
				$mime['fdf'] = "application/vnd.fdf";
				$mime['fif'] = "application/fractals";
				$mime['fif'] = "image/fif";
				$mime['fli'] = "video/fli";
				$mime['fli'] = "video/x-fli";
				$mime['flo'] = "image/florian";
				$mime['fmf'] = "video/x-atomic3d-feature";
				$mime['for'] = "text/plain";
				$mime['for'] = "text/x-fortran";
				$mime['fpx'] = "image/vnd.fpx";
				$mime['fpx'] = "image/vnd.net-fpx";
				$mime['frl'] = "application/freeloader";
				$mime['funk'] = "audio/make";
				$mime['g'] = "text/plain";
				$mime['g3'] = "image/g3fax";
				$mime['gif'] = "image/gif";
				$mime['gl'] = "video/gl";
				$mime['gl'] = "video/x-gl";
				$mime['gsd'] = "audio/x-gsm";
				$mime['gsm'] = "audio/x-gsm";
				$mime['gsp'] = "application/x-gsp";
				$mime['gss'] = "application/x-gss";
				$mime['gtar'] = "application/x-gtar";
				$mime['gz'] = "application/x-compressed";
				$mime['gz'] = "application/x-gzip";
				$mime['gzip'] = "application/x-gzip";
				$mime['gzip'] = "multipart/x-gzip";
				$mime['h'] = "text/plain";
				$mime['h'] = "text/x-h";
				$mime['hdf'] = "application/x-hdf";
				$mime['help'] = "application/x-helpfile";
				$mime['hgl'] = "application/vnd.hp-hpgl";
				$mime['hh'] = "text/plain";
				$mime['hh'] = "text/x-h";
				$mime['hlb'] = "text/x-script";
				$mime['hlp'] = "application/hlp";
				$mime['hlp'] = "application/x-helpfile";
				$mime['hlp'] = "application/x-winhelp";
				$mime['hpg'] = "application/vnd.hp-hpgl";
				$mime['hpgl'] = "application/vnd.hp-hpgl";
				$mime['hqx'] = "application/binhex";
				$mime['hqx'] = "application/binhex4";
				$mime['hqx'] = "application/mac-binhex";
				$mime['hqx'] = "application/mac-binhex40";
				$mime['hqx'] = "application/x-binhex40";
				$mime['hqx'] = "application/x-mac-binhex40";
				$mime['hta'] = "application/hta";
				$mime['htc'] = "text/x-component";
				$mime['htm'] = "text/html";
				$mime['html'] = "text/html";
				$mime['htmls'] = "text/html";
				$mime['htt'] = "text/webviewhtml";
				$mime['htx'] = "text/html";
				$mime['ice'] = "x-conference/x-cooltalk";
				$mime['ico'] = "image/x-icon";
				$mime['idc'] = "text/plain";
				$mime['ief'] = "image/ief";
				$mime['iefs'] = "image/ief";
				$mime['iges'] = "application/iges";
				$mime['iges'] = "model/iges";
				$mime['igs'] = "application/iges";
				$mime['igs'] = "model/iges";
				$mime['ima'] = "application/x-ima";
				$mime['imap'] = "application/x-httpd-imap";
				$mime['inf'] = "application/inf";
				$mime['ins'] = "application/x-internett-signup";
				$mime['ip'] = "application/x-ip2";
				$mime['isu'] = "video/x-isvideo";
				$mime['it'] = "audio/it";
				$mime['iv'] = "application/x-inventor";
				$mime['ivr'] = "i-world/i-vrml";
				$mime['ivy'] = "application/x-livescreen";
				$mime['jam'] = "audio/x-jam";
				$mime['jav'] = "text/plain";
				$mime['jav'] = "text/x-java-source";
				$mime['java'] = "text/plain";
				$mime['java'] = "text/x-java-source";
				$mime['jcm'] = "application/x-java-commerce";
				$mime['jfif'] = "image/jpeg";
				$mime['jfif'] = "image/pjpeg";
				$mime['jfif-tbnl'] = "image/jpeg";
				$mime['jpe'] = "image/jpeg";
				$mime['jpe'] = "image/pjpeg";
				$mime['jpeg'] = "image/jpeg";
				$mime['jpeg'] = "image/pjpeg";
				$mime['jpg'] = "image/jpeg";
				$mime['jpg'] = "image/pjpeg";
				$mime['jps'] = "image/x-jps";
				$mime['js'] = "application/x-javascript";
				$mime['jut'] = "image/jutvision";
				$mime['kar'] = "audio/midi";
				$mime['kar'] = "music/x-karaoke";
				$mime['ksh'] = "application/x-ksh";
				$mime['ksh'] = "text/x-script.ksh";
				$mime['la'] = "audio/nspaudio";
				$mime['la'] = "audio/x-nspaudio";
				$mime['lam'] = "audio/x-liveaudio";
				$mime['latex'] = "application/x-latex";
				$mime['lha'] = "application/lha";
				$mime['lha'] = "application/octet-stream";
				$mime['lha'] = "application/x-lha";
				$mime['lhx'] = "application/octet-stream";
				$mime['list'] = "text/plain";
				$mime['lma'] = "audio/nspaudio";
				$mime['lma'] = "audio/x-nspaudio";
				$mime['log'] = "text/plain";
				$mime['lsp'] = "application/x-lisp";
				$mime['lst'] = "text/plain";
				$mime['lsx'] = "text/x-la-asf";
				$mime['ltx'] = "application/x-latex";
				$mime['lzh'] = "application/octet-stream";
				$mime['lzh'] = "application/x-lzh";
				$mime['lzx'] = "application/lzx";
				$mime['lzx'] = "application/octet-stream";
				$mime['lzx'] = "application/x-lzx";
				$mime['m'] = "text/plain";
				$mime['m'] = "text/x-m";
				$mime['m1v'] = "video/mpeg";
				$mime['m2a'] = "audio/mpeg";
				$mime['m2v'] = "video/mpeg";
				$mime['m3u'] = "audio/x-mpequrl";
				$mime['man'] = "application/x-troff-man";
				$mime['map'] = "application/x-navimap";
				$mime['mar'] = "text/plain";
				$mime['mbd'] = "application/mbedlet";
				$mime['mcd'] = "application/mcad";
				$mime['mcd'] = "application/x-mathcad";
				$mime['mcf'] = "image/vasa";
				$mime['mcf'] = "text/mcf";
				$mime['mcp'] = "application/netmc";
				$mime['me'] = "application/x-troff-me";
				$mime['mht'] = "message/rfc822";
				$mime['mhtml'] = "message/rfc822";
				$mime['mid'] = "application/x-midi";
				$mime['mid'] = "audio/midi";
				$mime['mid'] = "audio/x-mid";
				$mime['mid'] = "audio/x-midi";
				$mime['mid'] = "music/crescendo";
				$mime['mid'] = "x-music/x-midi";
				$mime['midi'] = "application/x-midi";
				$mime['midi'] = "audio/midi";
				$mime['midi'] = "audio/x-mid";
				$mime['midi'] = "audio/x-midi";
				$mime['midi'] = "music/crescendo";
				$mime['midi'] = "x-music/x-midi";
				$mime['mif'] = "application/x-frame";
				$mime['mif'] = "application/x-mif";
				$mime['mime'] = "message/rfc822";
				$mime['mime'] = "www/mime";
				$mime['mjpg'] = "video/x-motion-jpeg";
				$mime['mm'] = "application/base64";
				$mime['mm'] = "application/x-meme";
				$mime['mme'] = "application/base64";
				$mime['mod'] = "audio/mod";
				$mime['mod'] = "audio/x-mod";
				$mime['moov'] = "video/quicktime";
				$mime['mov'] = "video/quicktime";
				$mime['movie'] = "video/x-sgi-movie";
				$mime['mp2'] = "audio/mpeg";
				$mime['mp2'] = "audio/x-mpeg";
				$mime['mp2'] = "video/mpeg";
				$mime['mp2'] = "video/x-mpeg";
				$mime['mp2'] = "video/x-mpeq2a";
				$mime['mp3'] = "audio/mpeg3";
				$mime['mp3'] = "audio/x-mpeg-3";
				$mime['mp3'] = "video/mpeg";
				$mime['mp3'] = "video/x-mpeg";
				$mime['mpa'] = "audio/mpeg";
				$mime['mpa'] = "video/mpeg";
				$mime['mpc'] = "application/x-project";
				$mime['mpe'] = "video/mpeg";
				$mime['mpeg'] = "video/mpeg";
				$mime['mpg'] = "audio/mpeg";
				$mime['mpg'] = "video/mpeg";
				$mime['mpga'] = "audio/mpeg";
				$mime['mpp'] = "application/vnd.ms-project";
				$mime['mpt'] = "application/x-project";
				$mime['mpv'] = "application/x-project";
				$mime['mpx'] = "application/x-project";
				$mime['mrc'] = "application/marc";
				$mime['ms'] = "application/x-troff-ms";
				$mime['mv'] = "video/x-sgi-movie";
				$mime['my'] = "audio/make";
				$mime['nap'] = "image/naplps";
				$mime['naplps'] = "image/naplps";
				$mime['nc'] = "application/x-netcdf";
				$mime['nif'] = "image/x-niff";
				$mime['niff'] = "image/x-niff";
				$mime['nix'] = "application/x-mix-transfer";
				$mime['nsc'] = "application/x-conference";
				$mime['nvd'] = "application/x-navidoc";
				$mime['o'] = "application/octet-stream";
				$mime['oda'] = "application/oda";
				$mime['omc'] = "application/x-omc";
				$mime['omcd'] = "application/x-omcdatamaker";
				$mime['omcr'] = "application/x-omcregerator";
				$mime['p'] = "text/x-pascal";
				$mime['p10'] = "application/pkcs10";
				$mime['p10'] = "application/x-pkcs10";
				$mime['p12'] = "application/pkcs-12";
				$mime['p12'] = "application/x-pkcs12";
				$mime['p7a'] = "application/x-pkcs7-signature";
				$mime['p7c'] = "application/pkcs7-mime";
				$mime['p7c'] = "application/x-pkcs7-mime";
				$mime['p7m'] = "application/pkcs7-mime";
				$mime['p7m'] = "application/x-pkcs7-mime";
				$mime['p7r'] = "application/x-pkcs7-certreqresp";
				$mime['p7s'] = "application/pkcs7-signature";
				$mime['part'] = "application/pro_eng";
				$mime['pas'] = "text/pascal";
				$mime['pbm'] = "image/x-portable-bitmap";
				$mime['pcl'] = "application/x-pcl";
				$mime['pct'] = "image/x-pict";
				$mime['pcx'] = "image/x-pcx";
				$mime['pdb'] = "chemical/x-pdb";
				$mime['pdf'] = "application/pdf";
				$mime['pfunk'] = "audio/make";
				$mime['pgm'] = "image/x-portable-graymap";
				$mime['pgm'] = "image/x-portable-greymap";
				$mime['pic'] = "image/pict";
				$mime['pict'] = "image/pict";
				$mime['pkg'] = "application/x-newton-compatible-pkg";
				$mime['pl'] = "text/plain";
				$mime['pl'] = "text/x-script.perl";
				$mime['plx'] = "application/x-pixclscript";
				$mime['pm'] = "image/x-xpixmap";
				$mime['pm4'] = "application/x-pagemaker";
				$mime['pm5'] = "application/x-pagemaker";
				$mime['png'] = "image/png";
				$mime['pnm'] = "application/x-portable-anymap";
				$mime['pnm'] = "image/x-portable-anymap";
				$mime['pot'] = "application/mspowerpoint";
				$mime['pot'] = "application/vnd.ms-powerpoint";
				$mime['pov'] = "model/x-pov";
				$mime['ppa'] = "application/vnd.ms-powerpoint";
				$mime['ppm'] = "image/x-portable-pixmap";
				$mime['pps'] = "application/mspowerpoint";
				$mime['pps'] = "application/vnd.ms-powerpoint";
				$mime['ppt'] = "application/mspowerpoint";
				$mime['ppt'] = "application/powerpoint";
				$mime['ppt'] = "application/vnd.ms-powerpoint";
				$mime['ppt'] = "application/x-mspowerpoint";
				$mime['ppz'] = "application/mspowerpoint";
				$mime['pre'] = "application/x-freelance";
				$mime['prt'] = "application/pro_eng";
				$mime['ps'] = "application/postscript";
				$mime['psd'] = "application/octet-stream";
				$mime['pvu'] = "paleovu/x-pv";
				$mime['pwz'] = "application/vnd.ms-powerpoint";
				$mime['py'] = "text/x-script.phyton";
				$mime['pyc'] = "applicaiton/x-bytecode.python";
				$mime['qcp'] = "audio/vnd.qcelp";
				$mime['qd3'] = "x-world/x-3dmf";
				$mime['qd3d'] = "x-world/x-3dmf";
				$mime['qif'] = "image/x-quicktime";
				$mime['qt'] = "video/quicktime";
				$mime['qtc'] = "video/x-qtc";
				$mime['qti'] = "image/x-quicktime";
				$mime['qtif'] = "image/x-quicktime";
				$mime['ra'] = "audio/x-pn-realaudio";
				$mime['ra'] = "audio/x-pn-realaudio-plugin";
				$mime['ra'] = "audio/x-realaudio";
				$mime['ram'] = "audio/x-pn-realaudio";
				$mime['ras'] = "application/x-cmu-raster";
				$mime['ras'] = "image/cmu-raster";
				$mime['ras'] = "image/x-cmu-raster";
				$mime['rast'] = "image/cmu-raster";
				$mime['rexx'] = "text/x-script.rexx";
				$mime['rf'] = "image/vnd.rn-realflash";
				$mime['rgb'] = "image/x-rgb";
				$mime['rm'] = "application/vnd.rn-realmedia";
				$mime['rm'] = "audio/x-pn-realaudio";
				$mime['rmi'] = "audio/mid";
				$mime['rmm'] = "audio/x-pn-realaudio";
				$mime['rmp'] = "audio/x-pn-realaudio";
				
				$mime['rmp'] = "audio/x-pn-realaudio-plugin";
				$mime['rng'] = "application/ringing-tones";
				$mime['rng'] = "application/vnd.nokia.ringing-tone";
				$mime['rnx'] = "application/vnd.rn-realplayer";
				$mime['roff'] = "application/x-troff";
				$mime['rp'] = "image/vnd.rn-realpix";
				$mime['rpm'] = "audio/x-pn-realaudio-plugin";
				$mime['rt'] = "text/richtext";
				$mime['rt'] = "text/vnd.rn-realtext";
				$mime['rtf'] = "application/rtf";
				$mime['rtf'] = "application/x-rtf";
				$mime['rtf'] = "text/richtext";
				$mime['rtx'] = "application/rtf";
				$mime['rtx'] = "text/richtext";
				$mime['rv'] = "video/vnd.rn-realvideo";
				$mime['s'] = "text/x-asm";
				$mime['s3m'] = "audio/s3m";
				$mime['saveme'] = "application/octet-stream";
				$mime['sbk'] = "application/x-tbook";
				$mime['scm'] = "application/x-lotusscreencam";
				$mime['scm'] = "text/x-script.guile";
				$mime['scm'] = "text/x-script.scheme";
				$mime['scm'] = "video/x-scm";
				$mime['sdml'] = "text/plain";
				$mime['sdp'] = "application/sdp";
				$mime['sdp'] = "application/x-sdp";
				$mime['sdr'] = "application/sounder";
				$mime['sea'] = "application/sea";
				$mime['sea'] = "application/x-sea";
				$mime['set'] = "application/set";
				$mime['sgm'] = "text/sgml";
				$mime['sgm'] = "text/x-sgml";
				$mime['sgml'] = "text/sgml";
				$mime['sgml'] = "text/x-sgml";
				$mime['sh'] = "application/x-bsh";
				$mime['sh'] = "application/x-sh";
				$mime['sh'] = "application/x-shar";
				$mime['sh'] = "text/x-script.sh";
				$mime['shar'] = "application/x-bsh";
				$mime['shar'] = "application/x-shar";
				$mime['shtml'] = "text/html";
				$mime['shtml'] = "text/x-server-parsed-html";
				$mime['sid'] = "audio/x-psid";
				$mime['sit'] = "application/x-sit";
				$mime['sit'] = "application/x-stuffit";
				$mime['skd'] = "application/x-koan";
				$mime['skm'] = "application/x-koan";
				$mime['skp'] = "application/x-koan";
				$mime['skt'] = "application/x-koan";
				$mime['sl'] = "application/x-seelogo";
				$mime['smi'] = "application/smil";
				$mime['smil'] = "application/smil";
				$mime['snd'] = "audio/basic";
				$mime['snd'] = "audio/x-adpcm";
				$mime['sol'] = "application/solids";
				$mime['spc'] = "application/x-pkcs7-certificates";
				$mime['spc'] = "text/x-speech";
				$mime['spl'] = "application/futuresplash";
				$mime['spr'] = "application/x-sprite";
				$mime['sprite'] = "application/x-sprite";
				$mime['src'] = "application/x-wais-source";
				$mime['ssi'] = "text/x-server-parsed-html";
				$mime['ssm'] = "application/streamingmedia";
				$mime['sst'] = "application/vnd.ms-pki.certstore";
				$mime['step'] = "application/step";
				$mime['stl'] = "application/sla";
				$mime['stl'] = "application/vnd.ms-pki.stl";
				$mime['stl'] = "application/x-navistyle";
				$mime['stp'] = "application/step";
				$mime['sv4cpio'] = "application/x-sv4cpio";
				$mime['sv4crc'] = "application/x-sv4crc";
				$mime['svf'] = "image/vnd.dwg";
				$mime['svf'] = "image/x-dwg";
				$mime['svr'] = "application/x-world";
				$mime['svr'] = "x-world/x-svr";
				$mime['swf'] = "application/x-shockwave-flash";
				$mime['t'] = "application/x-troff";
				$mime['talk'] = "text/x-speech";
				$mime['tar'] = "application/x-tar";
				$mime['tbk'] = "application/toolbook";
				$mime['tbk'] = "application/x-tbook";
				$mime['tcl'] = "application/x-tcl";
				$mime['tcl'] = "text/x-script.tcl";
				$mime['tcsh'] = "text/x-script.tcsh";
				$mime['tex'] = "application/x-tex";
				$mime['texi'] = "application/x-texinfo";
				$mime['texinfo'] = "application/x-texinfo";
				$mime['text'] = "application/plain";
				$mime['text'] = "text/plain";
				$mime['tgz'] = "application/gnutar";
				$mime['tgz'] = "application/x-compressed";
				$mime['tif'] = "image/tiff";
				$mime['tif'] = "image/x-tiff";
				$mime['tiff'] = "image/tiff";
				$mime['tiff'] = "image/x-tiff";
				$mime['tr'] = "application/x-troff";
				$mime['tsi'] = "audio/tsp-audio";
				$mime['tsp'] = "application/dsptype";
				$mime['tsp'] = "audio/tsplayer";
				$mime['tsv'] = "text/tab-separated-values";
				$mime['turbot'] = "image/florian";
				$mime['txt'] = "text/plain";
				$mime['uil'] = "text/x-uil";
				$mime['uni'] = "text/uri-list";
				$mime['unis'] = "text/uri-list";
				$mime['unv'] = "application/i-deas";
				$mime['uri'] = "text/uri-list";
				$mime['uris'] = "text/uri-list";
				$mime['ustar'] = "application/x-ustar";
				$mime['ustar'] = "multipart/x-ustar";
				$mime['uu'] = "application/octet-stream";
				$mime['uu'] = "text/x-uuencode";
				$mime['uue'] = "text/x-uuencode";
				$mime['vcd'] = "application/x-cdlink";
				$mime['vcs'] = "text/x-vcalendar";
				$mime['vda'] = "application/vda";
				$mime['vdo'] = "video/vdo";
				$mime['vew'] = "application/groupwise";
				$mime['viv'] = "video/vivo";
				$mime['viv'] = "video/vnd.vivo";
				$mime['vivo'] = "video/vivo";
				$mime['vivo'] = "video/vnd.vivo";
				$mime['vmd'] = "application/vocaltec-media-desc";
				$mime['vmf'] = "application/vocaltec-media-file";
				$mime['voc'] = "audio/voc";
				$mime['voc'] = "audio/x-voc";
				$mime['vos'] = "video/vosaic";
				$mime['vox'] = "audio/voxware";
				$mime['vqe'] = "audio/x-twinvq-plugin";
				$mime['vqf'] = "audio/x-twinvq";
				$mime['vql'] = "audio/x-twinvq-plugin";
				$mime['vrml'] = "application/x-vrml";
				$mime['vrml'] = "model/vrml";
				$mime['vrml'] = "x-world/x-vrml";
				$mime['vrt'] = "x-world/x-vrt";
				$mime['vsd'] = "application/x-visio";
				$mime['vst'] = "application/x-visio";
				$mime['vsw'] = "application/x-visio";
				$mime['w60'] = "application/wordperfect6.0";
				$mime['w61'] = "application/wordperfect6.1";
				$mime['w6w'] = "application/msword";
				$mime['wav'] = "audio/wav";
				$mime['wav'] = "audio/x-wav";
				$mime['wb1'] = "application/x-qpro";
				$mime['wbmp'] = "image/vnd.wap.wbmp";
				$mime['web'] = "application/vnd.xara";
				$mime['wiz'] = "application/msword";
				$mime['wk1'] = "application/x-123";
				$mime['wmf'] = "windows/metafile";
				$mime['wml'] = "text/vnd.wap.wml";
				$mime['wmlc'] = "application/vnd.wap.wmlc";
				$mime['wmls'] = "text/vnd.wap.wmlscript";
				$mime['wmlsc'] = "application/vnd.wap.wmlscriptc";
				$mime['word'] = "application/msword";
				$mime['wp'] = "application/wordperfect";
				$mime['wp5'] = "application/wordperfect";
				$mime['wp5'] = "application/wordperfect6.0";
				$mime['wp6'] = "application/wordperfect";
				$mime['wpd'] = "application/wordperfect";
				$mime['wpd'] = "application/x-wpwin";
				$mime['wq1'] = "application/x-lotus";
				$mime['wri'] = "application/mswrite";
				$mime['wri'] = "application/x-wri";
				$mime['wrl'] = "application/x-world";
				$mime['wrl'] = "model/vrml";
				$mime['wrl'] = "x-world/x-vrml";
				$mime['wrz'] = "model/vrml";
				$mime['wrz'] = "x-world/x-vrml";
				$mime['wsc'] = "text/scriplet";
				$mime['wsrc'] = "application/x-wais-source";
				$mime['wtk'] = "application/x-wintalk";
				$mime['xbm'] = "image/x-xbitmap";
				$mime['xbm'] = "image/x-xbm";
				$mime['xbm'] = "image/xbm";
				$mime['xdr'] = "video/x-amt-demorun";
				$mime['xgz'] = "xgl/drawing";
				$mime['xif'] = "image/vnd.xiff";
				$mime['xl'] = "application/excel";
				$mime['xla'] = "application/excel";
				$mime['xla'] = "application/x-excel";
				$mime['xla'] = "application/x-msexcel";
				$mime['xlb'] = "application/excel";
				$mime['xlb'] = "application/vnd.ms-excel";
				$mime['xlb'] = "application/x-excel";
				$mime['xlc'] = "application/excel";
				$mime['xlc'] = "application/vnd.ms-excel";
				$mime['xlc'] = "application/x-excel";
				$mime['xld'] = "application/excel";
				$mime['xld'] = "application/x-excel";
				$mime['xlk'] = "application/excel";
				$mime['xlk'] = "application/x-excel";
				$mime['xll'] = "application/excel";
				$mime['xll'] = "application/vnd.ms-excel";
				$mime['xll'] = "application/x-excel";
				$mime['xlm'] = "application/excel";
				$mime['xlm'] = "application/vnd.ms-excel";
				$mime['xlm'] = "application/x-excel";
				$mime['xls'] = "application/excel";
				$mime['xls'] = "application/vnd.ms-excel";
				$mime['xls'] = "application/x-excel";
				$mime['xls'] = "application/x-msexcel";
				$mime['xlt'] = "application/excel";
				$mime['xlt'] = "application/x-excel";
				$mime['xlv'] = "application/excel";
				$mime['xlv'] = "application/x-excel";
				$mime['xlw'] = "application/excel";
				$mime['xlw'] = "application/vnd.ms-excel";
				$mime['xlw'] = "application/x-excel";
				$mime['xlw'] = "application/x-msexcel";
				$mime['xm'] = "audio/xm";
				$mime['xml'] = "application/xml";
				$mime['xml'] = "text/xml";
				$mime['xmz'] = "xgl/movie";
				$mime['xpix'] = "application/x-vnd.ls-xpix";
				$mime['xpm'] = "image/x-xpixmap";
				$mime['xpm'] = "image/xpm";
				$mime['x-png'] = "image/png";
				$mime['xsr'] = "video/x-amt-showrun";
				$mime['xwd'] = "image/x-xwd";
				$mime['xyz'] = "chemical/x-pdb";
				$mime['z'] = "application/x-compress";
				$mime['zip'] = "application/zip";
				$mime['zoo'] = "application/octet-stream";
				$mime['zsh'] = "text/x-script.zsh";
				
				$tipo_archivo = strtolower ( substr( strstr ( basename ( $link_libro ), "." ), 1 ) );
				
				$url_partida = explode("/",$link_libro);
				
				header('Cache-Control: no-store, no-cache, must-revalidate'); 
				header('Cache-Control: pre-check=0, post-check=0, max-age=0');
				header("Pragma: no-cache");
				header("Expires: 0");
				header('Content-Transfer-Encoding: none');	
				header('Content-type: '.$mime[$tipo_archivo].'');
				header("content-disposition: attachment;filename=".str_replace(" ", "_", $url_partida[count($url_partida)-1])."");
				
				readfile("../".$link_libro);
			}else{
				header("Location: ../index.php");	
			}
		}else{
			header("Location: ../index.php");	
		}
	}else{
		header("Location: ../index.php");	
	}
?>