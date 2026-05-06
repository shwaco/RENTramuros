// tours step 2 

export async function fetchToursData() {
    try {
        const [attrRes, pkgRes, vehRes] = await Promise.all([
            fetch('../../backend/api/ui/attractions/get_attractions.php'),
            fetch('../../backend/api/ui/packages/get_packages.php'),
            fetch('../../backend/api/ui/vehicles/get_vehicles.php') 
        ]);

        const attrJson = await attrRes.json();
        const pkgJson = await pkgRes.json();
        const vehJson = await vehRes.json();
        const attractions = (attrJson.data || []).map(a => ({
            ...a,
            fee: parseFloat(a.fee),
            main_image: a.main_img 
        }));

        const packages = (pkgJson.data || []).map(p => ({
            ...p,
            price: parseFloat(p.price),
            itinerary_ids: p.itinerary ? p.itinerary.split(',').map(id => parseInt(id.trim())) : []
        }));

        const vehicles = (vehJson.data || []).map(v => ({
            ...v,
            price: parseFloat(v.price)
        }));

        return { attractions, packages, vehicles };

    } catch (error) {
        console.error("Failed to fetch tours data:", error);
        return null;
    }
}


export async function submitBookingRequest(payload) {
    try {
        const response = await fetch('../../backend/api/actions/receipt/post_bookings.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (response.ok && result.status === "success") {
            console.log("Booking saved with ID:", result.unique_id);
            return true;
        } else {
            console.error("Database responded with an error:", result.message);
            return false; 
        }
    } catch (error) {
        console.error("Network Error (Server might be unreachable):", error);
        return false;
    }
}