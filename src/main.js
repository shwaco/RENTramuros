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
            <img src="https://visitfortsantiago.com/wp-content/uploads/2019/02/rotator3.jpeg" style="width: 300px; border-radius: 8px; margin-bottom: 5px;">
            <h3>Fort Santiago</h3>
        </div>
    `)
    .on('mouseover', function (e) {
        this.openPopup();
    })
    .on('mouseout', function (e) {
        this.closePopup(); 
    })
    .on('click', function (e) {
    
    map.flyTo(e.latlng, 18, {
        animate:true,
        duration: 1.0
    });

    document.getElementById('panel-title').innerText = "Fort Santiago";
        document.getElementById('panel-img').src = "https://visitfortsantiago.com/wp-content/uploads/2019/02/rotator3.jpeg";
        document.getElementById('panel-text').innerText = "Known as one of the most important historical landmarks in the Philippines, Fort Santiago is a monumental fortress known for its beautiful gardens, Spanish stone gates, and powerful stories from centuries past in the colonial era. As you walk through the fort, explore the exhibits that shed light on history, and walk the same paths taken by the national hero: Jose Rizal.";
        document.getElementById('panel-btn').href = "#";
        
        document.getElementById('side-panel').classList.add('open');
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
        document.getElementById('panel-title').innerText = "Minor Basilica";
        document.getElementById('panel-img').src = "https://cjvoyage.home.blog/wp-content/uploads/2018/12/13.jpg";
        document.getElementById('panel-text').innerText = "One of the most iconic churches in the Philippines, this grand cathedral with its Romanesque architecture, stained glass windows, and lively interior make it the main must-visit spiritual and cultural landmark to visit.  ";
        document.getElementById('panel-btn').href = "#";
        
        document.getElementById('side-panel').classList.add('open');
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
        document.getElementById('panel-title').innerText = "San Agustin Church";
        document.getElementById('panel-img').src = "https://www.filipinoart.ph/newsroom/wp-content/uploads/2020/04/San-Agustin-Museum3.jpg";
        document.getElementById('panel-text').innerText = "The oldest stone church in the Philippines and a UNESCO World Heritage Site, it is famous for its Baroque architecture, and vividly painted ceilings. Inside the convent museum, visitors can explore centuries of history, artifacts, stories, and religious arts from the colonial period.";
        document.getElementById('panel-btn').href = "#";
        
        document.getElementById('side-panel').classList.add('open');
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
        document.getElementById('panel-title').innerText = "Casa Manila";
        document.getElementById('panel-img').src = "https://gttp.images.tshiftcdn.com/316055/x/0/courtyard-of-casa-manila-inside-intramuros.jpg";
        document.getElementById('panel-text').innerText = "Travel back in time with a beautifully reconstructed Spanish colonial house. Explore and see in-person how wealthy Filipino families lived during the time, featuring antique furniture, elegant decor, and authentic architecture from the colonial era. ";
        document.getElementById('panel-btn').href = "#";
        
        document.getElementById('side-panel').classList.add('open');
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
        document.getElementById('panel-title').innerText = "Baluarte de San Diego";
        document.getElementById('panel-img').src = "https://upload.wikimedia.org/wikipedia/commons/9/99/Baluarte_de_San_Diego_Intramuros.jpg";
        document.getElementById('panel-text').innerText = "A peaceful spot perfect for sightseeing, photography, and learning about the defenses of the walled city. The Baluarte is known for its picturesque view and lush gardens surrounded by the walls, which are part of the oldest stone fortifications in Intramuros. ";
        document.getElementById('panel-btn').href = "#";

        document.getElementById('side-panel').classList.add('open');
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
        document.getElementById('panel-title').innerText = "Rizal Shrine";
        document.getElementById('panel-img').src = "https://virmuze-bucket.s3.amazonaws.com/gallery_slideshow0/exhibit_gallery_image/2022/05/27/9655ce3_exhibit_gallery_image.jpeg";
        document.getElementById('panel-text').innerText = "Also inside Fort Santiago, the Rizal Shrine is dedicated to the life and works of the Philippine’s national hero. Showcasing photographs, manuscripts, and authentic memorabilia, learn about the hero’s final days and his overall impact on Philippine history.";
        document.getElementById('panel-btn').href = "#";
        
        document.getElementById('side-panel').classList.add('open');
    });
var marker7 = L.marker([14.591682371482037, 120.97248916771434], { icon: customIcon }).addTo(map)
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
        document.getElementById('panel-title').innerText = "Palacio Del Gobernado";
        document.getElementById('panel-img').src = "https://thumbs.dreamstime.com/b/palacio-del-gobernador-intramuros-manila-philippine-philippines-76280677.jpg";
        document.getElementById('panel-text').innerText = "An important government and historical site in the heart of Intramuros. It was once the residence of Spanish governor-generals, and stands as a symbol of political history during the colonial era. ";
        document.getElementById('panel-btn').href = "#";
        
        document.getElementById('side-panel').classList.add('open');
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
        document.getElementById('panel-title').innerText = "Museo De Intramuros";
        document.getElementById('panel-img').src = "https://images.esquiremag.ph/esquiremagph/images/gallery/8205/main-museo-de-intramuros-2.jpg";
        document.getElementById('panel-text').innerText = "A must-visit museum, it features beautifully preserved artwork, sculptures, and artifacts from different parts of the country. It showcases the religious and cultural heritage of the Philippines during the colonial era, showing its rich history.";
        document.getElementById('panel-btn').href = "#";
        
        document.getElementById('side-panel').classList.add('open');
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
        document.getElementById('panel-title').innerText = "Silahi's Art and Artifacts Inc";
        document.getElementById('panel-img').src = "https://dynamic-media-cdn.tripadvisor.com/media/photo-o/12/9f/08/81/we-would-like-to-introduce.jpg?w=1200&h=-1&s=1";
        document.getElementById('panel-text').innerText = "A treasure trove of Filipino craftsmanship, browse and shop from a wide collection of traditional artworks, handmade souvenirs, antiques, and cultural pieces. It celebrates the creativity and cultural heritage of the Philippines.";
        document.getElementById('panel-btn').href = "#";

        document.getElementById('side-panel').classList.add('open');
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
        document.getElementById('panel-title').innerText = "Rizal's Bagumbayan Light and Sound Museum";
        document.getElementById('panel-img').src = "https://thumbs.dreamstime.com/b/bagumbayan-light-sound-museum-facade-intramuros-walled-city-manila-philippines-ph-oct-october-173379833.jpg";
        document.getElementById('panel-text').innerText = "Experience history through an immersive storytelling performance. Learn about the life and martyrdom of Jose Rizal in a stylish and engaging way through dramatic lights, storytelling, and music.";
        document.getElementById('panel-btn').href = "#";
        
        document.getElementById('side-panel').classList.add('open');
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
        document.getElementById('panel-title').innerText = "Barbara's Heritage Restaurant";
        document.getElementById('panel-img').src = "https://media-cdn.tripadvisor.com/media/photo-s/02/73/db/3d/filename-img-0509-jpg.jpg";
        document.getElementById('panel-text').innerText = "Have a taste of history and experience it. Authentic Filipino cuisine in a historic setting, it is a unique dining experience that blends food, music, and heritage. It is known for its traditional dishes and cultural performances. ";
        document.getElementById('panel-btn').href = "#";
        
        document.getElementById('side-panel').classList.add('open');
    });

var marker12 = L.marker([14.589703266065198, 120.97540935422217], { icon: customIcon }).addTo(map)
    .bindPopup(`
        <div style="text-align: center;">
            <img src="https://lh3.googleusercontent.com/p/AF1QipOt2mEJR3tsVMVdiH_EJOpYK5-jQSnbb33YnWo0=s680-w680-h510-rw" style="width: 300px; border-radius: 8px; margin-bottom: 10px;">
            <h3>Bambike Ecotours</h3>
        </div>
    `)
    .on('mouseover', function (e) {
        this.openPopup();
    })
    .on('mouseout', function (e) {
        this.closePopup(); 
    })
    .on('click', function (e) {
        document.getElementById('panel-title').innerText = "Bambike Ecotours";
        document.getElementById('panel-img').src = "https://lh3.googleusercontent.com/p/AF1QipOt2mEJR3tsVMVdiH_EJOpYK5-jQSnbb33YnWo0=s680-w680-h510-rw";
        document.getElementById('panel-text').innerText = "Explore the walled city in a fun, unique, and eco-friendly way. Handmade bamboo bikes let visitors ride through the historical walls learning about its rich culture and heritage at your own pace, or through guided tours. It is a fun, relaxing, sustainable, and memorable way to experience and see the charm of Intramuros.";
        document.getElementById('panel-btn').href = "#";
        
        document.getElementById('side-panel').classList.add('open');
    });

const searchMap = {
    "Fort Santiago": marker1,
    "Minor Basilica": marker2,
    "San Agustin Church": marker3,
    "Casa Manila": marker4,
    "Baluarte de San Diego": marker5,
    "Rizal Shrine": marker6,
    "Palacio Del Gobernador": marker7,
    "Museo De Intramuros": marker8,
    "Silahi's Art And Artifacts Inc": marker9,
    "Rizal's Bagumbayan Light and Sound Museum": marker10,
    "Barbara's Heritage Restaurant": marker11,
    "Bambike Ecotours": marker12
};

document.getElementById('search-btn').addEventListener('click', function() {
    let query = document.getElementById('search-input').value;
    
    if (searchMap[query]) {
        let targetMarker = searchMap[query];

        map.flyTo(targetMarker.getLatLng(), 18);
        
        targetMarker.fire('click'); 

        setTimeout(function() {
            targetMarker.openPopup();
        });
        
    } else {
        alert("Location not found.");
    }
});




