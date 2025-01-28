//import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss';

import * as bootstrap from 'bootstrap';
import Core from "./app/core.js";



const App = new Core();
let linkButtons = document.querySelectorAll('.like-button');

// Loop over them and prevent submission
document.addEventListener("DOMContentLoaded", (event) => {
    'use strict'

    const forms = document.querySelectorAll('.needs-validation')

    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
        }, false)
    })
});

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))


createLinkAction(linkButtons,setLike,'data-url','data-value')

function createLinkAction(elements,customFunction,attribute,id, eventAction = "click"){
    Array.prototype.slice.call(elements)
        .forEach(function (element) {
            console.log("element")

            let link = App.getAttribute(element,attribute);
            let value = App.getAttribute(element,id);

            element.addEventListener(eventAction,function(e) {
                    e.preventDefault();
                    customFunction(link,value);
                },
                false)
        })
}

function loadPage(link) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        App.findOneBy('#sample_items').innerHTML = this.responseText;
    }
    xhttp.open("POST", link);
    xhttp.send();
    return null
}

function setLike(link, value){
    let likeIcon = App.findOneBy('.like-icon-'+value);
    let likeLoader = App.findOneBy('.like-loader-'+value);
    let matchDiv = App.findOneBy('#like-match-'+value);
    App.setClass(likeIcon,'d-none');
    App.setClass(likeLoader,'d-none',true);
    let data = {};
    data.id = value;
    let json = JSON.stringify(data);
    let xhr = new XMLHttpRequest();
    xhr.open('POST', link,true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            App.setClass(likeIcon,'d-none',true);
            App.setClass(likeLoader,'d-none');
            if(response.match === true) {
                matchDiv.innerHTML = 'Ihr kennt euch gegenseitig';
            } else {
                matchDiv.innerHTML = '';
            }
            if(response.sampleHasLike === true)
            {

                App.setClass(likeIcon,'fa-solid');
                App.setClass(likeIcon,'fa-regular',true);
            } else {
                App.setClass(likeIcon,'fa-regular');
                App.setClass(likeIcon,'fa-solid',true);
            }
            App.findOneBy('.like-number-'+value).innerHTML = response.likes;

        }
    };
    xhr.send(json);
}

let $formInputFields = document.querySelectorAll('.input-comment');

Array.prototype.slice.call($formInputFields)
    .forEach(function ($field) {
        let $i = 0;
        addMultipleEventListener($field,['keyup','change','cut','click','focus'],validateForm)
    })

function addMultipleEventListener(element, events, handler) {
    events.forEach(e => element.addEventListener(e, handler))
}

function validateForm($i){
    let submitButton = App.findOneBy('#submit_'+App.getAttribute(this,'name'));
    if(this.value === "" && this.hasAttribute('required')){
        $i = 1;
    }
    if($i === 1){
        submitButton.classList.add('disabled');
    } else {
        submitButton.classList.remove('disabled');
        submitButton.removeAttribute('disabled');
    }

}

function updateUnreadMessagesUI(count) {
    const unreadCountDiv = document.getElementById('unread-count');
    const mailboxHeaderIcon = document.getElementById('mail-marker');
    if (unreadCountDiv && mailboxHeaderIcon) {
        if (count > 0) {
            if(unreadCountDiv.classList.contains('d-none')) {
                unreadCountDiv.classList.remove('d-none');
                mailboxHeaderIcon.classList.add('marker-secondary');
                unreadCountDiv.innerText = count;

            }
        } else {
            if(!unreadCountDiv.classList.contains('d-none')) {
                unreadCountDiv.classList.add('d-none');
                mailboxHeaderIcon.classList.remove('marker-secondary');
                unreadCountDiv.innerText = "";
            }
        }
    }
}

function checkUnreadMessages() {
    const unreadCountDiv = document.getElementById('unread-count');


    if(unreadCountDiv) {
        const userId = unreadCountDiv.getAttribute('data-id'); // Benutzer-ID aus dem Div auslesen

        fetch('/api/unread-messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ userId: userId }), // Benutzer-ID im Body senden
        })
            .then(response => response.json())
            .then(data => {
                updateUnreadMessagesUI(data); // Aktualisiere das UI mit der Anzahl oder blende es aus
            })
            .catch(error => {
                console.error('Fehler beim Abrufen der ungelesenen Nachrichten:', error);
            });
    }

}
checkUnreadMessages();
setInterval(checkUnreadMessages, 10 * 1000); // Ruft alle 5 Sekunden `checkUnreadMessages` auf

const toastTrigger = document.getElementById('liveToastBtn')
const toastLiveExample = document.getElementById('liveToast')

if (toastTrigger) {
    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
    toastTrigger.addEventListener('click', () => {
        toastBootstrap.show()
    })
}

function initSingleSlimSelect(element, settings) {
    App.setClass(element, 'form-select',true);
    App.setClass(element, 'custom-select');

    new SlimSelect({
        select: element,
        settings: settings
    })
}

function initMultiSlimSelect(element, settings) {
    App.setClass(element, 'form-select',true);
    App.setClass(element, 'custom-select');

    new SlimSelect({
        select: element,
        settings: settings
    })
}

const selectSingleElements = document.querySelectorAll('[class^="slim-select-single-"]');
const selectMultiElements = document.querySelectorAll('[class^="slim-select-multi-"]');

let singleSettings = {
    showSearch: false,
    hideSelected: true,
}

Array.prototype.slice.call(selectSingleElements)
    .forEach(function (element) {
        initSingleSlimSelect(element,singleSettings);
    })

Array.prototype.slice.call(selectMultiElements)
    .forEach(function (element) {
        let multiSettings = {
            allowDeselect: true,
            maxSelected: element.getAttribute('data-max') ?? 5,
            showSearch: true, // used in example
            focusSearch: true, // used in example
            searchText: 'nichts gefunden', // used in example
            searchPlaceholder: 'Suchen ...', // used in example
            searchHighlight: true,
            maxValuesShown: 1,
            maxValuesMessage: '{number} ausgewählt',
            closeOnSelect: false,
            placeholderText: 'auswählen'
        }
        initMultiSlimSelect(element,multiSettings);
    })



