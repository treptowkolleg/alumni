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

import $ from "jquery";

import DataTable from 'datatables.net-dt';
import 'datatables.net-buttons-dt';
import 'datatables.net-responsive-dt';
import 'datatables.net-dt/css/dataTables.dataTables.min.css'

import SlimSelect from 'slim-select'
import 'slim-select/dist/slimselect.min.css'

let tableOptions = {
    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
}
let tableElement = document.getElementById('datatable');

if(tableElement) {
    let table = new DataTable(tableElement, {
        fixedHeader: true,
        responsive: true,
        searching: true,
        dom: '<"top"l><"datatable-wrapper"t><"bottom"p><"clear">',
        pageLength: 25,
        lengthChange: false,
        order: [[2, 'desc']],
        "language": tableOptions,
        columnDefs: [
            { width: '70%', targets: 1 },
            { type: 'date-de', targets: 2 }
        ]
    });

    table.on('init', function () {
        let search = document.getElementById("table-search");

        search.addEventListener("keyup", function () {
            table.search(search.value).draw();
        });
    });

    document.querySelector("tbody").addEventListener("click", function (event) {
        const row = event.target.closest("tr");
        if (row && row.dataset.url) {
            window.location.href = row.dataset.url;
        }
    });


    $('tbody').on('click', 'tr', function() {
        window.location.href = $(this).data('url')
    });
}




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


let eventFollowButtons = document.querySelectorAll('.event-follow-btn');
createLinkAction(eventFollowButtons,toggleEvent,'data-url','data-id')

function toggleEvent(link, value){
    let likeIcon = App.findOneBy('.event-follow-icon-'+value);
    let likeLoader = App.findOneBy('.loader-'+value);
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

            if(response.sampleHasLike === true)
            {
                App.setClass(likeIcon,'ri-star-fill');
                App.setClass(likeIcon,'ri-star-line',true);
            } else {
                App.setClass(likeIcon,'ri-star-line');
                App.setClass(likeIcon,'ri-star-fill',true);
            }
        }
    };
    xhr.send(json);
}

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
                matchDiv.innerHTML = 'Ihr kennt euch';
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


document.addEventListener('DOMContentLoaded', () => {
    const cookieBanner = document.getElementById('cookie-banner');
    const cookieModalElement = document.getElementById('cookie-modal');
    const acceptAllButton = document.getElementById('accept-all');
    const customizeButton = document.getElementById('customize');
    const saveSettingsButton = document.getElementById('save-cookie-settings');

    // Bootstrap-Modal-Instanz
    if(cookieModalElement) {
        const cookieModal = new bootstrap.Modal(cookieModalElement);

        acceptAllButton.addEventListener('click', () => {
            fetch('/api/cookie-consent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ categories: ['necessary_cookies_only'] })
            }).then(response => {
                if (response.ok) {
                    cookieBanner.style.display = 'none';
                }
            });
        });

        // Einstellungen anpassen
        customizeButton.addEventListener('click', () => {
            cookieModal.show();
        });

        // Cookie-Einstellungen speichern
        saveSettingsButton.addEventListener('click', () => {
            const categories = Array.from(document.querySelectorAll('input[name="cookie-category"]:checked'))
                .map(input => input.value);

            fetch('/api/cookie-consent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ categories })
            }).then(response => {
                if (response.ok) {
                    cookieModal.hide();
                    cookieBanner.style.display = 'none';
                }
            });
        });

    }


    // Alle Cookies akzeptieren

});
