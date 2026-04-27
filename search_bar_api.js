// THE DATA FETCHER (API LOGIC)
export async function fetchAttractionData() {
    try {
        const response = await fetch('search_attraction_data.json');
        const data = await response.json();
        return data; 
    } catch (error) {
        console.error("Error loading the attraction data:", error);
        return []; // Returns an empty array if it fails so the site doesn't crash
    }
}