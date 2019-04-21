const $ = require('jquery');
Handlebars = require('handlebars');
require('bootstrap');
require('../css/main.css');
require('bootstrap/dist/css/bootstrap.css');
require('font-awesome/css/font-awesome.css');

$(function () {
    let template = Handlebars.compile($("#shop-template").html());
    let placesService = new google.maps.places.PlacesService(map);
    let distanceMatrixService = new google.maps.DistanceMatrixService();
    let shopsJson = window.shopsJson.map(function (item) {
        return item.shopId;
    });


    window.console.log(shopsJson);

    let location = new google.maps.LatLng(33.592242399999996, -7.629155900000001);
    let request = {
        location: location,
        radius: "500",
        type: ["store"],
        fields: ["name", "formatted_address", "place_id", "yarn insgeometry", "photos"]
    };


    function callback(results, status) {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
            results.forEach(function (place, index) {
                if (-1 === shopsJson.indexOf(place.place_id)) {

                    let shopPhoto = place.icon;
                    if (place.photos && place.photos.length) {
                        shopPhoto = place.photos[0].getUrl({"maxWidth": 200, "maxHeight": 150});
                    }
                    let context = {
                        image: shopPhoto,
                        title: place.name,
                        address: place.vicinity,
                        shopId: place.place_id
                    };
                    $("#shops").append(template(context));

                    $("#btnLike_" + place.place_id).click(function () {
                        //call webservice

                        function success(data, status) {
                            $("#div_shop_" + place.place_id).hide(1000);
                        }

                        function error(data, status) {
                            alert("Data: " + data + "\nStatus: " + status);
                        }

                        let req = {
                            shopId: place.place_id,
                            photoUrl: shopPhoto,
                            latitude: place.geometry.location.lat(),
                            longitude: place.geometry.location.lng(),
                            icon: place.icon,
                            reference: place.reference,
                            shopName: place.name,
                            address: place.vicinity
                        };

                        $.ajax({
                            type: "POST",
                            url: "/like",
                            data: req,
                            success: success,
                            error: error,
                            dataType: "json"
                        });

                    });


                    $("#btnDislike_" + place.place_id).click(function () {
                        //call webservice

                        function success(data, status) {
                            $("#div_shop_" + place.place_id).hide(1000);
                        }

                        function error(data, status) {
                            alert("Data: " + data + "\nStatus: " + status);
                        }

                        let req = {
                            shopId: place.place_id,
                            photoUrl: shopPhoto,
                            latitude: place.geometry.location.lat(),
                            longitude: place.geometry.location.lng(),
                            icon: place.icon,
                            reference: place.reference,
                            shopName: place.name,
                            address: place.vicinity
                        };

                        $.ajax({
                            type: "POST",
                            url: "/dislike",
                            data: req,
                            success: success,
                            error: error,
                            dataType: "json"
                        });

                    });

                }
            });
        }
    }


    // function callback2 (results, status){
    //     if(status === "OK"){
    //         window.console.log(results);
    //     }
    // }

    placesService.nearbySearch(request, callback);
});







