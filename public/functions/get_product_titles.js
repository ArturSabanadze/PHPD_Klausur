function returnProductTitlesByType(productType) {
    return fetch(`/api_middleware/title_node.php?library=${productType}`)
        .then(response => response.json())
        .then(data => data.titles || [])
        .catch(error => {
            console.error('Error fetching product titles:', error);
            return [];
        });
}
