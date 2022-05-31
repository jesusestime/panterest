/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './scss/app.scss';

// start the Stimulus application
import './bootstrap';
import $ from 'jquery';
import 'bootstrap';


//system reply Js code when we click reply send id of pin
window.onload=()=>{
          document.querySelectorAll("[data-reply]").forEach(element=>{
                    element.addEventListener("click",function(){
                              document.querySelector("#comment_parentid").value=this.dataset.id;
                    })
          })
}













