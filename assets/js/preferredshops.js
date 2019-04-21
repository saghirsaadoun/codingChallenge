const $ = require('jquery');
Handlebars = require('handlebars');
require('bootstrap');
require('../css/main.css');
require('bootstrap/dist/css/bootstrap.css');
require('font-awesome/css/font-awesome.css');

$(function () {


    let shopsJson = window.shopsJson;

    shopsJson.forEach(function (item) {

        $("#btnRemove_" + item.shopId).click(function () {
            //call webservice

            function success(data, status) {
                $("#div_shop_" + item.shopId).hide(1000);
            }

            function error (data, status){
                alert("Data: " + data + "\nStatus: " + status);
            }

            let req = {
                shopId: item.shopId,
            };

            $.ajax({
                type: "POST",
                url: "/remove",
                data: req,
                success: success,
                error: error,
                dataType: "json"
            });

        });
    });





});







