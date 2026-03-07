import './style.css';
import * as L from 'leaflet';
import 'leaflet/dist/leaflet.css';

window.L =L;

const map = L.map('map').setView([14.590370542944203, 120.97540387935484], 17);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);  

const customIcon = L.icon({
    iconUrl: "https://cdn-icons-png.flaticon.com/512/18911/18911242.png",
    iconSize: [60, 60]
});

var marker1 = L.marker([14.594368660501456, 120.9704119965505], { icon: customIcon }).addTo(map)
    .bindPopup(`
        <div style="text-align: center;">
            <img src="https://visitfortsantiago.com/wp-content/uploads/2019/02/rotator3.jpeg" style="width: 300px; border-radius: 8px; margin-bottom: 10px;">
            <h3>Fort Santiago</h3>
            <p style="font-size: 12px; color: gray; margin: 0;">(Click pin for details)</p>
        </div>
    `)
    .on('mouseover', function (e) {
        this.openPopup();
    })
    .on('mouseout', function (e) {
        this.closePopup(); 
    })
    .on('click', function (e) {
        document.getElementById('info-title').innerText = "Fort Santiago";
        document.getElementById('info-text').innerText = "";
        document.getElementById('info-btn').href = "";
        
        document.getElementById('square-info-box').style.display = 'block';
    });
var marker2 = L.marker([14.591924681662595, 120.97341080739596], { icon: customIcon }).addTo(map)
    .bindPopup(`
        <div style="text-align: center;">
            <img src="https://cjvoyage.home.blog/wp-content/uploads/2018/12/13.jpg" style="width: 300px; border-radius: 8px; margin-bottom: 10px;">
            <h3>Minor Basilica</h3>
        </div>
    `)
    .on('mouseover', function (e) {
        this.openPopup();
    })
    .on('mouseout', function (e) {
        this.closePopup(); 
    })
    .on('click', function (e) {
        document.getElementById('info-title').innerText = "Minor Basilica";
        document.getElementById('info-text').innerText = "";
        document.getElementById('info-btn').href = "";
        
        document.getElementById('square-info-box').style.display = 'block';
    });
var marker3 = L.marker([14.589338241759469, 120.97513033287642], { icon: customIcon }).addTo(map)
    .bindPopup(`
        <div style="text-align: center;">
            <img src="https://www.filipinoart.ph/newsroom/wp-content/uploads/2020/04/San-Agustin-Museum3.jpg" style="width: 300px; border-radius: 8px; margin-bottom: 10px;">
            <h3>San Agustin Church (San Agustin Convent Museum)</h3>
        </div>
    `)
    .on('mouseover', function (e) {
        this.openPopup();
    })
    .on('mouseout', function (e) {
        this.closePopup(); 
    })
    .on('click', function (e) {
        document.getElementById('info-title').innerText = "San Agustin Church";
        document.getElementById('info-text').innerText = "";
        document.getElementById('info-btn').href = "";
        
        document.getElementById('square-info-box').style.display = 'block';
    });
var marker4 = L.marker([14.589731466055865, 120.97504055051868], { icon: customIcon }).addTo(map)
   .bindPopup(`
        <div style="text-align: center;">
            <img src="https://gttp.images.tshiftcdn.com/316055/x/0/courtyard-of-casa-manila-inside-intramuros.jpg" style="width: 300px; border-radius: 8px; margin-bottom: 10px;">
            <h3>Casa Manila</h3>
        </div>
    `)
    .on('mouseover', function (e) {
        this.openPopup();
    })
    .on('mouseout', function (e) {
        this.closePopup(); 
    })
    .on('click', function (e) {
        document.getElementById('info-title').innerText = "Casa Manila";
        document.getElementById('info-text').innerText = "";
        document.getElementById('info-btn').href = "";
        
        document.getElementById('square-info-box').style.display = 'block';
    });
var marker5 = L.marker([14.585692433320624, 120.97566282272292], { icon: customIcon }).addTo(map)
     .bindPopup(`
        <div style="text-align: center;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/99/Baluarte_de_San_Diego_Intramuros.jpg" style="width: 300px; border-radius: 8px; margin-bottom: 10px;">
            <h3>Baluarte de San Diego</h3>
        </div>
    `)
    .on('mouseover', function (e) {
        this.openPopup();
    })
    .on('mouseout', function (e) {
        this.closePopup(); 
    })
    .on('click', function (e) {
        document.getElementById('info-title').innerText = "Baluarte de San Diego";
        document.getElementById('info-text').innerText = "";
        document.getElementById('info-btn').href = "";
        
        document.getElementById('square-info-box').style.display = 'block';
    });
var marker6 = L.marker([14.59469639090222, 120.96959596771443], { icon: customIcon }).addTo(map)
    .bindPopup(`
        <div style="text-align: center;">
            <img src="https://virmuze-bucket.s3.amazonaws.com/gallery_slideshow0/exhibit_gallery_image/2022/05/27/9655ce3_exhibit_gallery_image.jpeg" style="width: 300px; border-radius: 8px; margin-bottom: 10px;">
            <h3>Rizal Shrine</h3>
        </div>
    `)
    .on('mouseover', function (e) {
        this.openPopup();
    })
    .on('mouseout', function (e) {
        this.closePopup(); 
    })
    .on('click', function (e) {
        document.getElementById('info-title').innerText = "Rizal Shrine";
        document.getElementById('info-text').innerText = "";
        document.getElementById('info-btn').href = "";
        
        document.getElementById('square-info-box').style.display = 'block';
    });
var marker7 = L.marker([14.592274153094746, 120.97247841938453], { icon: customIcon }).addTo(map)
    .bindPopup(`
        <div style="text-align: center;">
            <img src="https://thumbs.dreamstime.com/b/palacio-del-gobernador-intramuros-manila-philippine-philippines-76280677.jpg" style="width: 300px; border-radius: 8px; margin-bottom: 10px;">
            <h3>Palacio Del Gobernador</h3>
        </div>
    `)
    .on('mouseover', function (e) {
        this.openPopup();
    })
    .on('mouseout', function (e) {
        this.closePopup(); 
    })
    .on('click', function (e) {
        document.getElementById('info-title').innerText = "Palacio Del Gobernador";
        document.getElementById('info-text').innerText = "";
        document.getElementById('info-btn').href = "";
        
        document.getElementById('square-info-box').style.display = 'block';
    });
var marker8 = L.marker([14.590052863152057, 120.97345112538642], { icon: customIcon }).addTo(map)
    .bindPopup(`
        <div style="text-align: center;">
            <img src="https://images.esquiremag.ph/esquiremagph/images/gallery/8205/main-museo-de-intramuros-2.jpg" style="width: 300px; border-radius: 8px; margin-bottom: 10px;">
            <h3>Museo De Intramuros</h3>
        </div>
    `)
    .on('mouseover', function (e) {
        this.openPopup();
    })
    .on('mouseout', function (e) {
        this.closePopup(); 
    })
    .on('click', function (e) {
        document.getElementById('info-title').innerText = "Museo De Intramuros";
        document.getElementById('info-text').innerText = "";
        document.getElementById('info-btn').href = "";
        
        document.getElementById('square-info-box').style.display = 'block';
    });
var marker9 = L.marker([14.587684179514117, 120.97698311004227], { icon: customIcon }).addTo(map)
    .bindPopup(`
        <div style="text-align: center;">
            <img src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/12/9f/08/81/we-would-like-to-introduce.jpg?w=1200&h=-1&s=1" style="width: 300px; border-radius: 8px; margin-bottom: 10px;">
            <h3>Silahi's Art and Artifacts Inc</h3>
        </div>
    `)
    .on('mouseover', function (e) {
        this.openPopup();
    })
    .on('mouseout', function (e) {
        this.closePopup(); 
    })
    .on('click', function (e) {
        document.getElementById('info-title').innerText = "Silahi's Art And Artifacts Inc";
        document.getElementById('info-text').innerText = "";
        document.getElementById('info-btn').href = "";
        
        document.getElementById('square-info-box').style.display = 'block';
    });
var marker10 = L.marker([14.587148079969733, 120.97520281004242], { icon: customIcon }).addTo(map)
    .bindPopup(`
        <div style="text-align: center;">
            <img src="https://thumbs.dreamstime.com/b/bagumbayan-light-sound-museum-facade-intramuros-walled-city-manila-philippines-ph-oct-october-173379833.jpg" style="width: 300px; border-radius: 8px; margin-bottom: 10px;">
            <h3>Rizal’s Bagumbayan Light and Sound Museum</h3>
        </div>
    `)
    .on('mouseover', function (e) {
        this.openPopup();
    })
    .on('mouseout', function (e) {
        this.closePopup(); 
    })
    .on('click', function (e) {
        document.getElementById('info-title').innerText = "Rizal's Bagumbayan Light and Sound Museum";
        document.getElementById('info-text').innerText = "";
        document.getElementById('info-btn').href = "";
        
        document.getElementById('square-info-box').style.display = 'block';
    });
var marker11 = L.marker([14.589662875177895, 120.97510720819086], { icon: customIcon }).addTo(map)
    .bindPopup(`
        <div style="text-align: center;">
            <img src="https://media-cdn.tripadvisor.com/media/photo-s/02/73/db/3d/filename-img-0509-jpg.jpg" style="width: 300px; border-radius: 8px; margin-bottom: 10px;">
            <h3>Barbara's Heritage Restaurant</h3>
        </div>
    `)
    .on('mouseover', function (e) {
        this.openPopup();
    })
    .on('mouseout', function (e) {
        this.closePopup(); 
    })
    .on('click', function (e) {
        document.getElementById('info-title').innerText = "Barbara's Heritage Restaurant";
        document.getElementById('info-text').innerText = "";
        document.getElementById('info-btn').href = "";
        
        document.getElementById('square-info-box').style.display = 'block';
    });









