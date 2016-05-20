/*
 *  Google Map
 *
 */
 

window.google = window.google || {};
google.maps = google.maps || {};
(function() {
  
  function getScript(src) {
    document.write('<' + 'script src="' + src + '"' +
                   ' type="text/javascript"><' + '/script>');
  }
  
  var modules = google.maps.modules = {};
  google.maps.__gjsload__ = function(name, text) {
    modules[name] = text;
  };
  
  google.maps.Load = function(apiLoad) {
    delete google.maps.Load;
    apiLoad([0.009999999776482582,[[["http://mt0.googleapis.com/vt?lyrs=m@262000000\u0026src=api\u0026hl=ro-RO\u0026","http://mt1.googleapis.com/vt?lyrs=m@262000000\u0026src=api\u0026hl=ro-RO\u0026"],null,null,null,null,"m@262000000",["https://mts0.google.com/vt?lyrs=m@262000000\u0026src=api\u0026hl=ro-RO\u0026","https://mts1.google.com/vt?lyrs=m@262000000\u0026src=api\u0026hl=ro-RO\u0026"]],[["http://khm0.googleapis.com/kh?v=149\u0026hl=ro-RO\u0026","http://khm1.googleapis.com/kh?v=149\u0026hl=ro-RO\u0026"],null,null,null,1,"149",["https://khms0.google.com/kh?v=149\u0026hl=ro-RO\u0026","https://khms1.google.com/kh?v=149\u0026hl=ro-RO\u0026"]],[["http://mt0.googleapis.com/vt?lyrs=h@262000000\u0026src=api\u0026hl=ro-RO\u0026","http://mt1.googleapis.com/vt?lyrs=h@262000000\u0026src=api\u0026hl=ro-RO\u0026"],null,null,null,null,"h@262000000",["https://mts0.google.com/vt?lyrs=h@262000000\u0026src=api\u0026hl=ro-RO\u0026","https://mts1.google.com/vt?lyrs=h@262000000\u0026src=api\u0026hl=ro-RO\u0026"]],[["http://mt0.googleapis.com/vt?lyrs=t@132,r@262000000\u0026src=api\u0026hl=ro-RO\u0026","http://mt1.googleapis.com/vt?lyrs=t@132,r@262000000\u0026src=api\u0026hl=ro-RO\u0026"],null,null,null,null,"t@132,r@262000000",["https://mts0.google.com/vt?lyrs=t@132,r@262000000\u0026src=api\u0026hl=ro-RO\u0026","https://mts1.google.com/vt?lyrs=t@132,r@262000000\u0026src=api\u0026hl=ro-RO\u0026"]],null,null,[["http://cbk0.googleapis.com/cbk?","http://cbk1.googleapis.com/cbk?"]],[["http://khm0.googleapis.com/kh?v=84\u0026hl=ro-RO\u0026","http://khm1.googleapis.com/kh?v=84\u0026hl=ro-RO\u0026"],null,null,null,null,"84",["https://khms0.google.com/kh?v=84\u0026hl=ro-RO\u0026","https://khms1.google.com/kh?v=84\u0026hl=ro-RO\u0026"]],[["http://mt0.googleapis.com/mapslt?hl=ro-RO\u0026","http://mt1.googleapis.com/mapslt?hl=ro-RO\u0026"]],[["http://mt0.googleapis.com/mapslt/ft?hl=ro-RO\u0026","http://mt1.googleapis.com/mapslt/ft?hl=ro-RO\u0026"]],[["http://mt0.googleapis.com/vt?hl=ro-RO\u0026","http://mt1.googleapis.com/vt?hl=ro-RO\u0026"]],[["http://mt0.googleapis.com/mapslt/loom?hl=ro-RO\u0026","http://mt1.googleapis.com/mapslt/loom?hl=ro-RO\u0026"]],[["https://mts0.googleapis.com/mapslt?hl=ro-RO\u0026","https://mts1.googleapis.com/mapslt?hl=ro-RO\u0026"]],[["https://mts0.googleapis.com/mapslt/ft?hl=ro-RO\u0026","https://mts1.googleapis.com/mapslt/ft?hl=ro-RO\u0026"]],[["https://mts0.googleapis.com/mapslt/loom?hl=ro-RO\u0026","https://mts1.googleapis.com/mapslt/loom?hl=ro-RO\u0026"]]],["ro-RO","US",null,0,null,null,"http://maps.gstatic.com/mapfiles/","http://csi.gstatic.com","https://maps.googleapis.com","http://maps.googleapis.com"],["http://maps.gstatic.com/intl/ro_ro/mapfiles/api-3/16/11","3.16.11"],[2049165306],1,null,null,null,null,1,"",null,null,0,"http://khm.googleapis.com/mz?v=149\u0026",null,"https://earthbuilder.googleapis.com","https://earthbuilder.googleapis.com",null,"http://mt.googleapis.com/vt/icon",[["http://mt0.googleapis.com/vt","http://mt1.googleapis.com/vt"],["https://mts0.googleapis.com/vt","https://mts1.googleapis.com/vt"],[null,[[0,"m",262000000]],[null,"ro-RO","US",null,18,null,null,null,null,null,null,[[47],[37,[["smartmaps"]]]]],0],[null,[[0,"m",262000000]],[null,"ro-RO","US",null,18,null,null,null,null,null,null,[[47],[37,[["smartmaps"]]]]],3],[null,[[0,"m",262000000]],[null,"ro-RO","US",null,18,null,null,null,null,null,null,[[50],[37,[["smartmaps"]]]]],0],[null,[[0,"m",262000000]],[null,"ro-RO","US",null,18,null,null,null,null,null,null,[[50],[37,[["smartmaps"]]]]],3],[null,[[4,"t",132],[0,"r",132000000]],[null,"ro-RO","US",null,18,null,null,null,null,null,null,[[5],[37,[["smartmaps"]]]]],0],[null,[[4,"t",132],[0,"r",132000000]],[null,"ro-RO","US",null,18,null,null,null,null,null,null,[[5],[37,[["smartmaps"]]]]],3],[null,null,[null,"ro-RO","US",null,18],0],[null,null,[null,"ro-RO","US",null,18],3],[null,null,[null,"ro-RO","US",null,18],6],[null,null,[null,"ro-RO","US",null,18],0],["https://mts0.google.com/vt","https://mts1.google.com/vt"],"/maps/vt"],2,500,["http://geo0.ggpht.com/cbk?cb_client=maps_sv.uv_api_demo","http://www.gstatic.com/landmark/tour","http://www.gstatic.com/landmark/config","/maps/preview/reveal?authuser=0","/maps/preview/log204","/gen204?tbm=map","http://static.panoramio.com.storage.googleapis.com/photos/"]], loadScriptTime);
  };
  var loadScriptTime = (new Date).getTime();
  getScript("http://maps.gstatic.com/intl/ro_ro/mapfiles/api-3/16/11/main.js");
})();

<!-- ================================================== -->
<!-- =============== START GOOGLE MAP SETTINGS ================ -->
<!-- ================================================== -->

jQuery(document).ready(function(){

  var map;
  var lat = jQuery('#map-canvas').data('lat');
  var long = jQuery('#map-canvas').data('long');
  var myLatLng = new google.maps.LatLng(lat,long);

  function initialize() {

    var roadAtlasStyles = [
      {
        stylers: [
          { hue: "#00ffe6" },
          { saturation: -20 }
        ]
      },{
        featureType: "road",
        elementType: "geometry",
        stylers: [
          { lightness: 100 },
          { visibility: "simplified" }
        ]
      },{
        featureType: "road",
        elementType: "labels",
        stylers: [
          { visibility: "off" }
        ]
      }
    ];

    var mapOptions = {
        zoom: 13,
      center: myLatLng,
    disableDefaultUI: true,
    scrollwheel: false,
      navigationControl: false,
      mapTypeControl: false,
      scaleControl: false,
      draggable: false,
      mapTypeControlOptions: {
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'usroadatlas']
      }
    };

    var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    var img = jQuery('#map-canvas').data('img');
     
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        icon: img,
      title: ''
    });
    
    var contentString = '<div style="max-width: 300px" id="content">'+
        '<div id="bodyContent">'+
      '<h5 class="color-primary"><strong>StylishThemes</strong></h5>' +
        '<p style="font-size: 12px">Lorem ipsum dolor sit amet,' +
        'incididunt ut labore et dolore psum dolor magna aliqua.</p>'+
        '</div>'+
        '</div>';

    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });
    
    google.maps.event.addListener(marker, 'click', function() {
      infowindow.open(map,marker);
    });

    var styledMapOptions = {
      name: 'US Road Atlas'
    };

    var usRoadMapType = new google.maps.StyledMapType(
        roadAtlasStyles, styledMapOptions);

    map.mapTypes.set('usroadatlas', usRoadMapType);
    map.setMapTypeId('usroadatlas');
  }

  google.maps.event.addDomListener(window, 'load', initialize);
    
});

<!-- ================================================== -->
<!-- =============== END GOOGLE MAP SETTINGS ================ -->
<!-- ================================================== -->