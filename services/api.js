// retrieve popular attractions 
export async function getPopularAttractions() {
    try {
        const response = await fetch('backend/attractions/get_attractions.php');

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
        const response = await fetch('backend/attractions/get_attractions.php');

        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`)
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
