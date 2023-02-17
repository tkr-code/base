
// AdminLTE css
import './styles/adminlte/adminlte.css'

// import './scripts/adminlte/adminlte'  
// Admin 
import './styles/admin.css'
import './styles/cropper.css'
import Routing from '../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router';
import Routes from './js/routes.json'
import Cropper from 'cropperjs'
import './scripts/adminlte/adminlte.min.js'
import './scripts/adminlte/demo.js'
import './scripts/bootstrap.bundle.min.js'
import axios from 'axios'
// AdminLTE js 
import $ from 'jquery';
// import './scripts/admin/profil'


Routing.setRoutingData(Routes);

$(function () {
    console.log('jqury ready 2')

    //Ferme l'alerte apres 5s
    $(".alert").fadeTo(10000, 500).slideUp(500, function () {
        $(".alert").slideUp(500);
        });
})