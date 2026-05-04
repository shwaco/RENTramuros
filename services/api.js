// retrieve popular attractions 
export async function getPopularAttractions() {
    try {
        const response = await fetch('../../backend/api/ui/attractions/get_attractions.php');

        if (!response.ok) {
            throw new Error (`Response status: ${response.status}`);
        } else {
            const result = await response.json();
            const popData = result.data.filter((pop) => pop.attraction_type == 'Popular');
            return popData || [];
        } 

    } catch (error) {
        console.error(error.message);
        return [];
    } 
}

// retrieve recommended attractions
export async function getRecommendedAttractions() {
    try {
        const response = await fetch('../../backend/api/ui/attractions/get_attractions.php');

        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        } else {
            const result = await response.json();
            const recoData = result.data.filter(reco => reco.attraction_type === 'Recommended');
            return recoData || [];
        }

    } catch (error) {
        console.error(error.message);
        return [];
    }
} 

// retrieve packages
export async function getPackages() {
    try {
        const response = await fetch('../../backend/api/ui/packages/get_packages.php');

        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`); 
        } else {
            const result = await response.json();
            return result.data || [];
        }
    } catch (error) {
        console.error(error.message);
        return [];
    }
}

// retrieve upcoming events
export async function getUpcomingEvents() {
    try {
        const response = await fetch('../../backend/api/ui/upcoming_events/get_upcoming_events.php');

        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        } else {
            const result = await response.json();
            return result.data || [];
        }
    } catch (error) {
        console.Error(error.message);
        return [];
    }
}
