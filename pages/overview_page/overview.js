import { getPackages, getPopularAttractions, getRecommendedAttractions } from "../../services/api.js";

document.addEventListener('DOMContentLoaded', async () => { 
    const urlParams = new URLSearchParams(window.location.search);
    const itemType = urlParams.get('type');
    const itemId = urlParams.get('id');

    if (!itemType || !itemId) {
        document.getElementById('attraction-title').innerText = 'Error: Item Not Found';
        return;
    }

    if (itemType === 'attraction') {
        await loadAttractionData(itemId);
    } else if (itemType === 'package') {
        await loadPackageData(itemId); 
    }
});     

async function loadAttractionData(id) {
    const [popular, recommended] = await Promise.all([
        getPopularAttractions(),
        getRecommendedAttractions()
    ]);

    const allAttractions = [...popular, ...recommended];
    const data = allAttractions.find(a => a.attraction_id == id);

    if (!data) {
        document.getElementById('attraction-title').innerText = 'Attraction Not Found.'; 
        return;
    }

    document.getElementById('attraction-title').innerText = data.attraction_name;
    document.getElementById('attraction-address').innerText = data.address;
    document.getElementById('attraction-description').innerText = data.description;
    document.getElementById('attraction-hours').innerText = `Open: ${data.schedule}`;
    document.getElementById('attraction-price').innerText = `Entrance: ₱${data.fee}`;

    const gridBoxes = document.querySelectorAll('.box img');
    if(gridBoxes[0]) gridBoxes[0].src = `../../asset/img/${data.main_img}`;
    if(gridBoxes[1]) gridBoxes[1].src = `../../asset/img/${data.mini_one_img}`;
    if(gridBoxes[2]) gridBoxes[2].src = `../../asset/img/${data.mini_two_img}`;
    if(gridBoxes[3]) gridBoxes[3].src = `../../asset/img/${data.rec_img}`;
}

async function loadPackageData(id) {
    const packages = await getPackages();
    const pkg = packages.find(p => p.package_id == id);

    if (!pkg) {
        document.getElementById('attraction-title').innerText = 'Package Not Found.'; 
        return;
    }

    document.getElementById('attraction-title').innerText = pkg.package_name;
    document.getElementById('attraction-description').innerText = pkg.description;
    document.getElementById('attraction-price').innerText = `Package Price: ₱${pkg.price}`;
    
    document.getElementById('attraction-address').innerText = "Multiple Locations (See Itinerary)";
    document.getElementById('attraction-hours').style.display = 'none';

    if (pkg.itinerary) {
        const itineraryIds = pkg.itinerary.split(',').map(num => parseInt(num.trim()));
        
        const [popular, recommended] = await Promise.all([
            getPopularAttractions(),
            getRecommendedAttractions()
        ]);
        
        const allAttractions = [...popular, ...recommended];
        const includedAttractions = allAttractions.filter(a => itineraryIds.includes(parseInt(a.attraction_id)));

        // --- THE UPGRADED IMAGE FALLBACK FEATURE ---
        const gridBoxes = document.querySelectorAll('.box');
        
        if (gridBoxes[0]) {
            gridBoxes[0].querySelector('img').src = `../../asset/img/${pkg.image_file}`;
            gridBoxes[0].style.display = 'block';
        }

        const extraImages = [];
        const lastAttr = includedAttractions[includedAttractions.length - 1];

        for (let i = 0; i < Math.min(3, includedAttractions.length); i++) {
            extraImages.push(includedAttractions[i].main_img);
        }

        if (extraImages.length < 3 && lastAttr) {
            const backupImages = [lastAttr.mini_one_img, lastAttr.mini_two_img, lastAttr.rec_img];
            let backupIndex = 0;

            while (extraImages.length < 3 && backupIndex < backupImages.length) {
                if (backupImages[backupIndex]) { 
                    extraImages.push(backupImages[backupIndex]);
                }
                backupIndex++;
            }
        }

        for (let i = 1; i <= 3; i++) {
            if (gridBoxes[i]) {
                if (extraImages[i - 1]) { 
                    gridBoxes[i].querySelector('img').src = `../../asset/img/${extraImages[i - 1]}`;
                    gridBoxes[i].style.display = 'block'; 
                } else {
                    gridBoxes[i].style.display = 'none'; 
                }
            }
        }
        // ---------------------------------------- 
        
        // FIX 2: Actually draw the UI!
        buildItineraryUI(includedAttractions);
    }
} 

function buildItineraryUI(attractionsArray) {
    const descContainer = document.querySelector('.description-text'); 

    let htmlString = `
        <br><br>
        <h4 style="font-family: 'Roboto Condensed', sans-serif; font-size: 1.2rem; color: #8D230F; margin-bottom: 0.5rem;">
            Tour Itinerary
        </h4>
        <ul style="list-style-type: disc; padding-left: 1.5rem; line-height: 1.8;">
    `;

    attractionsArray.forEach(place => {
        htmlString += `<li><strong>${place.attraction_name}</strong> - ${place.address}</li>`;
    });

    htmlString += `</ul>`;


    descContainer.insertAdjacentHTML('beforeend', htmlString); 
}