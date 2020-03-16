/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

$(document).ready(function(){

    $(".container-create").click(function(e){
        e.preventDefault();
        console.log('test');
        $.ajax({url: "/create-card", success: function(result){
                $("Container").html(result);
            }});
    });
});
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
