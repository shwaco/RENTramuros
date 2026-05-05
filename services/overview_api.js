async function fetchOverviewData() {
    try {
        const response = await fetch('../../backend/api/ui/attractions/get_attractions.php');
        
        // Safety check to ensure the network request actually succeeded
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const database = await response.json();
        return database;
        
    } catch (error) {
        console.error("API Error: Could not fetch the database.", error);
        return null; // Return null so the main script knows it failed
    }
}