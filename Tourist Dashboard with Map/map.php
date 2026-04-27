<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <link rel="stylesheet" href="style.css">

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Intramuros Map</title>

  </head>
  <body>

    <div id="search-container">
        <input list="intramuros-locations" id="search-input" placeholder="Search popular tourist spots...">
        
        <datalist id="intramuros-locations">
            <option value="Fort Santiago">
            <option value="Minor Basilica">
            <option value="San Agustin Church">
            <option value="Casa Manila">
            <option value="Baluarte de San Diego">
            <option value="Rizal Shrine">
            <option value="Palacio Del Gobernador">
            <option value="Museo De Intramuros">
            <option value="Silahi's Art And Artifacts Inc">
            <option value="Rizal's Bagumbayan Light and Sound Museum">
            <option value="Barbara's Heritage Restaurant">
            <option value="Bambike Ecotours">
            <option value="Puerta Del Parian">
        </datalist>
        
        <button id="search-btn">🔎Search</button>
        <img id="map-logo" src="./RENTRAMUROS_LOGO_BLACK.svg" alt="RENTramuros-logo">
    </div>

    <div id="map"></div>
    <div id="side-panel">
        <button id="close-panel-btn" onclick="document.getElementById('side-panel').classList.remove('open')">X</button>
        
        <img id="panel-img" src="" alt="Location Image">
        
        <div class="panel-content">
            <h2 id="panel-title">Title</h2>
            <p id="panel-text">Details dito</p>
            <h3 id="panel-operating-hours-header">🕰️Operating Hours:</h3>
                <div id="panel-operating-hours-details"></div>
            </details>
            <a id="panel-btn" href="#" target="_blank" class="book-now-btn">
            Book Now</a>
        </div>
    </div>

    <div id="church-buttons">
        <button id="churches-btn" class="Churches-btn">⛪ Churches</button>
    </div>
    <div id="foodplaces-buttons">
        <button id="food-places-btn" class="Food-places-btn">🍽️ Food Places</button>
    </div>
    <div id="museums-buttons">
        <button id="museum-btn" class="Museum-btn">🖼️ Museums </button>
    </div>
    <div id="landmarks-buttons">
        <button id="landmark-btn" class="Landmark-btn">🏛️ Historical Landmarks </button>
    </div>
    <div id="rides-buttons">
        <button id="ride-btn" class="Rides-btn">🚲 Activities </button>
    </div>
    <div id="show-all-buttons">
        <button id="show-all-btn" class="Show-all-btn">📍 Show all pins</button>
    </div>
        <script src="main.js"></script>
</body>
</html>