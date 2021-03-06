
/**
 * @file
 * Javascript functions for getdirections module
 *
 * @author Bob Hutchinson http://drupal.org/user/52366
 * code derived from gmap_direx module
 * this is for googlemaps API version 3
*/
(function ($) {
  Drupal.getdirections = {};

  var dirservice;
  var dirrenderer;
  var unitsys;
  var llpatt = /[0-9.\-],[0-9.\-]/;
  var map;
  var scheme = 'http';
  var startIconUrl = scheme + "://www.google.com/mapfiles/dd-start.png";
  var endIconUrl = scheme + "://www.google.com/mapfiles/dd-end.png";
  var shadowIconUrl = scheme + "://www.google.com/mapfiles/shadow50.png";
  var tomarker;
  var frommarker;
  var trafficInfo;
  var bicycleInfo;
  var transitInfo;
  var traffictoggleState = 1;
  var bicycletoggleState = 1;
  var transittoggleState = 1;

  var panoramioLayer;
  var panoramiotoggleState = 1;

  // error codes
  function getdirectionserrcode(errcode) {
    var errstr;
    if (errcode == google.maps.DirectionsStatus.INVALID_REQUEST) {
      errstr = Drupal.t("The DirectionsRequest provided was invalid.");
    }
    else if (errcode == google.maps.DirectionsStatus.MAX_WAYPOINTS_EXCEEDED) {
      errstr = Drupal.t("Too many DirectionsWaypoints were provided in the DirectionsRequest. The total allowed waypoints is 8, plus the origin, and destination.");
    }
    else if (errcode == google.maps.DirectionsStatus.NOT_FOUND) {
      errstr = Drupal.t("At least one of the origin, destination, or waypoints could not be geocoded.");
    }
    else if (errcode == google.maps.DirectionsStatus.OVER_QUERY_LIMIT) {
      errstr = Drupal.t("The webpage has gone over the requests limit in too short a period of time.");
    }
    else if (errcode == google.maps.DirectionsStatus.REQUEST_DENIED) {
      errstr = Drupal.t("The webpage is not allowed to use the directions service.");
    }
    else if (errcode == google.maps.DirectionsStatus.UNKNOWN_ERROR) {
      errstr = Drupal.t("A directions request could not be processed due to a server error. The request may succeed if you try again.");
    }
    else if (errcode == google.maps.DirectionsStatus.ZERO_RESULTS) {
      errstr = Drupal.t("No route could be found between the origin and destination.");
    }
    return errstr;
  }

  function getgeoerrcode(errcode) {
    var errstr;
    if (errcode == google.maps.GeocoderStatus.ERROR) {
      errstr = Drupal.t("There was a problem contacting the Google servers.");
    }
    else if (errcode == google.maps.GeocoderStatus.INVALID_REQUEST) {
      errstr = Drupal.t("This GeocoderRequest was invalid.");
    }
    else if (errcode == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
      errstr = Drupal.t("The webpage has gone over the requests limit in too short a period of time.");
    }
    else if (errcode == google.maps.GeocoderStatus.REQUEST_DENIED) {
      errstr = Drupal.t("The webpage is not allowed to use the geocoder.");
    }
    else if (errcode == google.maps.GeocoderStatus.UNKNOWN_ERROR) {
      errstr = Drupal.t("A geocoding request could not be processed due to a server error. The request may succeed if you try again.");
    }
    else if (errcode == google.maps.GeocoderStatus.ZERO_RESULTS) {
      errstr = Drupal.t("No result was found for this GeocoderRequest.");
    }
    return errstr;
  }

  function renderdirections(request) {
    dirservice.route(request, function(response, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        dirrenderer.setDirections(response);
      } else {
        var err = getdirectionserrcode(status);
        alert(Drupal.t('Error') + ': ' + err);
      }
    });
  }

  function getRequest(fromAddress, toAddress, waypts) {
    var request = {
      origin: fromAddress,
      destination: toAddress
    };
    var tmode = $("#edit-travelmode").val();
    var trmode;
    if (tmode == 'walking') { trmode = google.maps.DirectionsTravelMode.WALKING; }
    else if (tmode == 'bicycling') { trmode = google.maps.DirectionsTravelMode.BICYCLING; }
    else if (tmode == 'transit') {
      trmode = google.maps.DirectionsTravelMode.TRANSIT;
      // transit dates
      if ($("#edit-transit-date-select").is('select')) {
        d = $("#edit-transit-dates-datepicker-popup-0").val();
        t = $("#edit-transit-dates-timeEntry-popup-1").val();
        if (d && t) {
          if (Drupal.settings.getdirections.transit_date_format == 'int') {
            d1 = d.split(' ');
            dmy = d1[0];
            d2 = dmy.split('/');
            d3 = d2[1] +'/' + d2[0] + '/' + d2[2];
            s = d3 + ' ' + t;
          }
          else {
            s = d + ' ' + t;
          }
          dp = Date.parse(s);
          tstamp = new Date(dp);
          date_dir = $("#edit-transit-date-select").val();
          tropts = '';
          if (date_dir == 'arrive') {
            tropts = {arrivalTime: tstamp};
          }
          else if (date_dir == 'depart') {
            tropts = {departureTime: tstamp};
          }
          if (tropts) {
            request.transitOptions = tropts;
          }
        }
      }
    }
    else { trmode = google.maps.DirectionsTravelMode.DRIVING; }
    request.travelMode = trmode;

    if (unitsys == 'imperial') { request.unitSystem = google.maps.DirectionsUnitSystem.IMPERIAL; }
    else { request.unitSystem = google.maps.DirectionsUnitSystem.METRIC; }

    var avoidh = false;
    if ($("#edit-travelextras-avoidhighways").attr('checked')) { avoidh = true; }
    request.avoidHighways = avoidh;

    var avoidt = false;
    if ($("#edit-travelextras-avoidtolls").attr('checked')) { avoidt = true; }
    request.avoidTolls = avoidt;

    var routealt = false;
    if ($("#edit-travelextras-altroute").attr('checked')) { routealt = true; }
    request.provideRouteAlternatives = routealt;

    if (waypts) {
      request.waypoints = waypts;
      request.optimizeWaypoints = true;
    }

    return request;
  }

  // from the form
  Drupal.getdirections.mygetDirections = function() {

    // collect form info from the DOM
    var from = $("input[name=from]").val();
    if ($("#edit-country-from").val()) {
      from += ', ' + $("#edit-country-from").val();
    }
    else if (from.match(llpatt)) {
      // we have lat,lon
      var farr = from.split(",");
      from = new google.maps.LatLng(farr[0], farr[1]);
    }

    var to = $("input[name=to]").val();
    if ($("#edit-country-to").val()) {
      to += ', ' + $("#edit-country-to").val();
    }
    else if (to.match(llpatt)) {
      // we have lat,lon
      var tarr = to.split(",");
      to = new google.maps.LatLng(tarr[0], tarr[1]);
    }
    // switch off markers
    if (tomarker && tomarker.getVisible() == true) {
      tomarker.setMap(null);
    }
    if (frommarker && frommarker.getVisible() == true) {
      frommarker.setMap(null);
    }

    var request = getRequest(from, to, '');
    renderdirections(request);

  };

  function makell(ll) {
    if (ll.match(llpatt)) {
      var arr = ll.split(",");
      var d = new google.maps.LatLng(parseFloat(arr[0]), parseFloat(arr[1]));
      return d;
    }
    return false;
  }

  // with waypoints
  function setDirectionsvia(lls) {
    var arr = lls.split('|');
    var len = arr.length;
    var wp = 0;
    var waypts = [];
    var from;
    var to;
    var via;
    for (var i = 0; i < len; i++) {
      if (i == 0) {
        from = makell(arr[i]);
      }
      else if (i == len-1) {
        to = makell(arr[i]);
      }
      else {
        wp++;
        if (wp < 24) {
          via = makell(arr[i]);
          waypts.push({
            location: via,
            stopover: true
          });
        }
      }
    }
    var request = getRequest(from, to, waypts);
    renderdirections(request);
  }

  function setDirectionsfromto(fromlatlon, tolatlon) {
    var from = makell(fromlatlon);
    var to = makell(tolatlon);
    var request = getRequest(from, to, '');
    renderdirections(request);
  }

  // Total distance and duration
  function computeTotals(result) {
    var meters = 0;
    var seconds = 0;
    var myroute = result.routes[0];
    for (i = 0; i < myroute.legs.length; i++) {
      meters += myroute.legs[i].distance.value;
      seconds += myroute.legs[i].duration.value;
    }

    if (Drupal.settings.getdirections.show_distance) {
      distance = meters * 0.001;
      if (unitsys == 'imperial') {
        distance = distance * 0.6214;
        distance = distance.toFixed(2) + ' mi';
      }
      else {
        distance = distance.toFixed(2) + ' km';
      }
      $("#getdirections_show_distance").html(Drupal.settings.getdirections.show_distance + ': ' + distance);
    }

    if (Drupal.settings.getdirections.show_duration) {
      mins = seconds * 0.016666667;
      minutes = mins.toFixed(0);
      // hours
      hours = 0;
      while (minutes >= 60 ) {
        minutes = minutes - 60;
        hours++;
      }
      // days
      days = 0;
      while (hours >= 24) {
        hours = hours - 24;
        days++;
      }
      duration = '';
      if (days > 0) {
        duration += Drupal.formatPlural(days, '1 day', '@count days') + ' ';
      }
      if (hours > 0) {
        //duration += hours + ' ' + (hours > 1 ? 'hours' : 'hour') + ' ';
        duration += Drupal.formatPlural(hours, '1 hour', '@count hours') + ' ';
     }
      if (minutes > 0) {
        //duration += minutes + ' ' + (minutes > 1 ? 'minutes' : 'minute');
        duration += Drupal.formatPlural(minutes, '1 minute', '@count minutes');
     }
      if (seconds < 60) {
        duration = Drupal.t('About 1 minute');
      }
      $("#getdirections_show_duration").html(Drupal.settings.getdirections.show_duration + ': ' + duration );
    }
  }

  function initialize() {
    var lat = parseFloat(Drupal.settings.getdirections.lat);
    var lng = parseFloat(Drupal.settings.getdirections.lng);
    var selzoom = parseInt(Drupal.settings.getdirections.zoom);
    var controltype = Drupal.settings.getdirections.controltype;
    var pancontrol = Drupal.settings.getdirections.pancontrol;
    var scale = Drupal.settings.getdirections.scale;
    var overview = Drupal.settings.getdirections.overview;
    var overview_opened = Drupal.settings.getdirections.overview_opened;
    var streetview_show = Drupal.settings.getdirections.streetview_show;
    var scrollw = Drupal.settings.getdirections.scrollwheel;
    var drag = Drupal.settings.getdirections.draggable;
    unitsys = Drupal.settings.getdirections.unitsystem;
    var maptype = (Drupal.settings.getdirections.maptype ? Drupal.settings.getdirections.maptype : '');
    var baselayers = (Drupal.settings.getdirections.baselayers ? Drupal.settings.getdirections.baselayers : '');
    var map_backgroundcolor = Drupal.settings.getdirections.map_backgroundcolor;

    var fromlatlon = (Drupal.settings.getdirections.fromlatlon ? Drupal.settings.getdirections.fromlatlon : '');
    var tolatlon = (Drupal.settings.getdirections.tolatlon ? Drupal.settings.getdirections.tolatlon : '');

    // pipe delim
    var latlons = (Drupal.settings.getdirections.latlons ? Drupal.settings.getdirections.latlons : '');
    if (Drupal.settings.getdirections.use_https) {
      scheme = 'https';
      startIconUrl = scheme + "://www.google.com/mapfiles/dd-start.png";
      endIconUrl = scheme + "://www.google.com/mapfiles/dd-end.png";
      shadowIconUrl = scheme + "://www.google.com/mapfiles/shadow50.png";
    }

    // menu type
    var mtc = Drupal.settings.getdirections.mtc;
    if (mtc == 'standard') { mtc = google.maps.MapTypeControlStyle.HORIZONTAL_BAR; }
    else if (mtc == 'menu' ) { mtc = google.maps.MapTypeControlStyle.DROPDOWN_MENU; }
    else { mtc = false; }

  // nav control type
  if (controltype == 'default') { controltype = google.maps.ZoomControlStyle.DEFAULT; }
  else if (controltype == 'small') { controltype = google.maps.ZoomControlStyle.SMALL; }
  else if (controltype == 'large') { controltype = google.maps.ZoomControlStyle.LARGE; }
  else { controltype = false; }

    // map type
    maptypes = [];
    if (maptype) {
      if (maptype == 'Map' && baselayers.Map) { maptype = google.maps.MapTypeId.ROADMAP; }
      if (maptype == 'Satellite' && baselayers.Satellite) { maptype = google.maps.MapTypeId.SATELLITE; }
      if (maptype == 'Hybrid' && baselayers.Hybrid) { maptype = google.maps.MapTypeId.HYBRID; }
      if (maptype == 'Physical' && baselayers.Physical) { maptype = google.maps.MapTypeId.TERRAIN; }
      if (baselayers.Map) { maptypes.push(google.maps.MapTypeId.ROADMAP); }
      if (baselayers.Satellite) { maptypes.push(google.maps.MapTypeId.SATELLITE); }
      if (baselayers.Hybrid) { maptypes.push(google.maps.MapTypeId.HYBRID); }
      if (baselayers.Physical) { maptypes.push(google.maps.MapTypeId.TERRAIN); }
    }
    else {
      maptype = google.maps.MapTypeId.ROADMAP;
      maptypes.push(google.maps.MapTypeId.ROADMAP);
      maptypes.push(google.maps.MapTypeId.SATELLITE);
      maptypes.push(google.maps.MapTypeId.HYBRID);
      maptypes.push(google.maps.MapTypeId.TERRAIN);
    }

    var mapOpts = {
      zoom: selzoom,
      center: new google.maps.LatLng(lat, lng),
      mapTypeControl: (mtc ? true : false),
      mapTypeControlOptions: {style: mtc,  mapTypeIds: maptypes},
      zoomControl: (controltype ? true : false),
      zoomControlOptions: {style: controltype},
      panControl: (pancontrol ? true : false),
      mapTypeId: maptype,
      scrollwheel: (scrollw ? true : false),
      draggable: (drag ? true : false),
      overviewMapControl: (overview ? true : false),
      overviewMapControlOptions: {opened: (overview_opened ? true : false)},
      streetViewControl: (streetview_show ? true : false),
      scaleControl: (scale ? true : false),
      scaleControlOptions: {style: google.maps.ScaleControlStyle.DEFAULT}
    };
    if (map_backgroundcolor) {
      mapOpts.backgroundColor = map_backgroundcolor;
    }

    map = new google.maps.Map(document.getElementById("getdirections_map_canvas"), mapOpts);

    if (Drupal.settings.getdirections.trafficinfo) {
      trafficInfo = new google.maps.TrafficLayer();
      if (Drupal.settings.getdirections.trafficinfo_state > 0) {
        trafficInfo.setMap(map);
        traffictoggleState = 1;
      }
      else {
        trafficInfo.setMap(null);
        traffictoggleState = 0;
      }
    }
    if (Drupal.settings.getdirections.bicycleinfo) {
      bicycleInfo = new google.maps.BicyclingLayer();
      if (Drupal.settings.getdirections.bicycleinfo_state > 0) {
        bicycleInfo.setMap(map);
        bicycletoggleState = 1;
      }
      else {
        bicycleInfo.setMap(null);
        bicycletoggleState = 0;
      }
    }
    if (Drupal.settings.getdirections.transitinfo) {
      transitInfo = new google.maps.TransitLayer();
      if (Drupal.settings.getdirections.transitinfo_state > 0) {
        transitInfo.setMap(map);
        transittoggleState = 1;
      }
      else {
        transitInfo.setMap(null);
        transittoggleState = 0;
      }
    }

    if (Drupal.settings.getdirections.panoramio_show) {
      panoramioLayer = new google.maps.panoramio.PanoramioLayer();
      if (Drupal.settings.getdirections.panoramio_state > 0) {
        panoramioLayer.setMap(map);
        panoramiotoggleState = 1;
      }
      else {
        panoramioLayer.setMap(null);
        panoramiotoggleState = 0;
      }
    }

    // define some icons
    var icon1 = new google.maps.MarkerImage(
      startIconUrl,
      new google.maps.Size(22, 34),
      // origin
      new google.maps.Point(0,0),
      // anchor
      new google.maps.Point(6, 20)
    );
    var icon3 = new google.maps.MarkerImage(
      endIconUrl,
      new google.maps.Size(22, 34),
      // origin
      new google.maps.Point(0,0),
      // anchor
      new google.maps.Point(6, 20)
    );
    var shadow1 = new google.maps.MarkerImage(
      shadowIconUrl,
      new google.maps.Size(37, 34),
      // origin
      new google.maps.Point(0,0),
      // anchor
      new google.maps.Point(6, 20)
    );
    var shape1 = {coord: [1,1,22,34], type: 'rect'};

    // any initial markers?
    var from =  $("input[name=from]").val();
    var to =  $("input[name=to]").val();

    if (from && from.match(llpatt)) {
      // we have lat,lon
      from = makell(from);
      frommarker = new google.maps.Marker({
        position: from,
        map: map,
        title: "From",
        icon: icon1,
        shadow: shadow1,
        shape: shape1
      });
      map.setCenter(from);
    }
    if (to && to.match(llpatt)) {
      // we have lat,lon
      to = makell(to);
      tomarker = new google.maps.Marker({
        position: to,
        map: map,
        title: "To",
        icon: icon3,
        shadow: shadow1,
        shape: shape1
      });
      map.setCenter(to);
    }

    dirrenderer = new google.maps.DirectionsRenderer();
    dirrenderer.setMap(map);
    dirrenderer.setPanel(document.getElementById("getdirections_directions"));

    if (Drupal.settings.getdirections.show_distance || Drupal.settings.getdirections.show_duration) {
      google.maps.event.addListener(dirrenderer, 'directions_changed', function() {
        computeTotals(dirrenderer.directions);
      });
    }

    dirservice = new google.maps.DirectionsService();

    if (fromlatlon && tolatlon) {
      setDirectionsfromto(fromlatlon, tolatlon);
    }

    if (latlons) {
      setDirectionsvia(latlons);
    }

    // transit dates
    if ($("#edit-travelmode").is('select') && $("#edit-transit-date-select").is('select')) {
      if ( $("#edit-travelmode").val() == 'transit') {
        $("#getdirections_transit_dates_wrapper").show();
      }
      else {
        $("#getdirections_transit_dates_wrapper").hide();
      }
      $("#edit-travelmode").change( function() {
        if ( $("#edit-travelmode").val() == 'transit') {
          $("#getdirections_transit_dates_wrapper").show();
        }
        else {
          $("#getdirections_transit_dates_wrapper").hide();
        }
      });
    }

    if (Drupal.settings.getdirections.geolocation_enable) {
      if (Drupal.settings.getdirections.geolocation_option == '1') {
        // html5
        if (navigator && navigator.geolocation) {
          $("#getdirections_geolocation_button_from").click( function () {
            doGeocode();
            return false;
          });
        }
      }
      else if (Drupal.settings.getdirections.geolocation_option == '2') {
        // smart_ip
        $("#getdirections_geolocation_button_from").click( function () {
          $.get(Drupal.settings.basePath + "getdirections/smart_ip", {}, function (loc) {
            if (loc) {
              lat = loc.latitude;
              lng = loc.longitude;
              if (lat && lng) {
                point = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
                doStart(point);
                if (! todone) {
                  map.panTo(point);
                }
                var adrs = '';
                var adrsarr = [];
                if (loc.route) {
                  adrsarr.push(loc.route);
                }
                if (loc.sublocality) {
                  adrsarr.push(loc.sublocality);
                }
                if (loc.locality) {
                  adrsarr.push(loc.locality);
                }
                if (loc.city) {
                  adrsarr.push(loc.city);
                }
                else if (loc.postal_town) {
                  adrsarr.push(loc.postal_town);
                }
                if (loc.region) {
                  adrsarr.push(loc.region);
                }
                if (loc.zip && loc.zip != '-') {
                  adrsarr.push(loc.zip);
                }
                else if (loc.postal_code_prefix) {
                  adrsarr.push(loc.postal_code_prefix);
                }
                if (loc.country) {
                  adrsarr.push(loc.country);
                }
                else if (loc.country_code) {
                  if (loc.country_code == 'UK') {
                    cc = 'GB';
                  }
                  else {
                    cc = loc.country_code;
                  }
                  adrsarr.push(cc);
                }
                adrs = adrsarr.join(', ');
                if ($("input[name=from]").val() == '') {
                  $("input[name=from]").val(adrs);
                }
              }
            }
          });
          return false;
        });
      }
      else if (Drupal.settings.getdirections.geolocation_option == '3') {
        $("#getdirections_geolocation_button_from").click( function () {
          $.get(Drupal.settings.basePath + "getdirections/ip_geoloc", {}, function (loc) {
            if (loc) {
              lat = loc.latitude;
              lng = loc.longitude;
              if (lat && lng) {
                point = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
                doStart(point);
                if (! todone) {
                  map.panTo(point);
                }
                var adrs = '';
                if (loc.formatted_address) {
                  adrs = loc.formatted_address;
                }
                if ($("input[name=from]").val() == '') {
                  $("input[name=from]").val(adrs);
                }
              }
            }
          });
          return false;
        });
      }
    }

    function doGeocode() {
      var statusdiv = '#getdirections_geolocation_status_from';
      var statusmsg = '';
      $(statusdiv).html(statusmsg);
      navigator.geolocation.getCurrentPosition(
        function(position) {
          lat = position.coords.latitude;
          lng = position.coords.longitude;
          point = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
          if (point) {
            doRGeocode(point);
          }
        },
        function(error) {
          statusmsg = Drupal.t("Sorry, I couldn't find your location using the browser") + ' ' + getdirectionserrcode(error.code) + ".";
          $(statusdiv).html(statusmsg);
        }, {maximumAge:10000}
      );
    }

    // reverse geocoding
    function doRGeocode(pt) {
      var input_ll = {'latLng': pt};
      // Create a Client Geocoder
      var geocoder = new google.maps.Geocoder();
      geocoder.geocode(input_ll, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[0]) {
            if ($("input[name=from]").val() == '') {
              $("input[name=from]").val(results[0].formatted_address);
            }
          }
        }
        else {
          var prm = {'!b': getgeoerrcode(status) };
          var msg = Drupal.t('Geocode was not successful for the following reason: !b', prm);
          alert(msg);
        }
      });
    }

  } // end initialise

  Drupal.getdirections.toggleTraffic = function() {
    if (traffictoggleState == 1) {
      trafficInfo.setMap(null);
      traffictoggleState = 0;
    }
    else {
      trafficInfo.setMap(map);
      traffictoggleState = 1;
    }
  };

  Drupal.getdirections.toggleBicycle = function() {
    if (bicycletoggleState == 1) {
      bicycleInfo.setMap(null);
      bicycletoggleState = 0;
    }
    else {
      bicycleInfo.setMap(map);
      bicycletoggleState = 1;
    }
  };
  Drupal.getdirections.toggleTransit = function() {
    if (transittoggleState == 1) {
      transitInfo.setMap(null);
      transittoggleState = 0;
    }
    else {
      transitInfo.setMap(map);
      transittoggleState = 1;
    }
  };
  Drupal.getdirections.togglePanoramio = function() {
    if (panoramiotoggleState == 1) {
      panoramioLayer.setMap(null);
      panoramiotoggleState = 0;
    }
    else {
      panoramioLayer.setMap(map);
      panoramiotoggleState = 1;
    }
  };

  Drupal.getdirections.toggleFromto = function() {
    var from = $("#edit-from").val();
    var countryfrom = false;
    if ($("#edit-country-from").val()) {
      countryfrom = $("#edit-country-from").val();
    }
    var to = $("#edit-to").val();
    var countryto = false;
    if ($("#edit-country-to").val()) {
      countryto = $("#edit-country-to").val();
    }
    $("#edit-from").val(to);
    $("#edit-to").val(from);
    if (countryfrom) {
      $("#edit-country-to").val(countryfrom);
    }
    if (countryto) {
      $("#edit-country-from").val(countryto);
    }
  };

  // gogogo
  Drupal.behaviors.getdirections = {
    attach: function() {
      initialize();
    }
  };

}(jQuery));
