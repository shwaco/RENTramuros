import './style.css';
import * as L from 'leaflet';
import 'leaflet/dist/leaflet.css';


window.L =L;


const map = L.map('map').setView([14.587002677902507, 120.9762717269021], 60);


L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);  

const CustomIcon = L.Icon.extend({
    imgUrl: 'https://www.flaticon.com/free-icon/pin_18911177?term=map+mark&page=1&position=13&origin=search&related_id=18911177', });

let marker = L.marker([14.587558341849931, 120.97613448407955]).addTo(map);

    marker.bindPopup("<b>Map Marker</b><br>This is a marker.").openPopup();