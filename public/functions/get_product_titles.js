async function returnProductTitlesByType(productType) {
    try {
        const response = await fetch(`api_middleware/title_node.php?library=${productType}`);
        const data = await response.json();
        return data.titles || [];
    } catch (error) {
        console.error('Error fetching product titles:', error);
        return [];
    }
}
