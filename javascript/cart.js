window.addEventListener("load", initSite)

async function initSite() {
   const products = await makeRequest("./api/recievers/orderReciever.php", "GET")
   console.log(products)
}

/* visa produkterna på startsidan */
async function displayProducts() {
    const products = await makeRequest("./api/recievers/orderReciever.php", "GET")
    console.log(products)
}


 async function deleteOneProduct() {
// för att ta bort EN produkt, onclick
    const products = await makeRequest("./api/recievers/productReciever.php", "POST")
    console.log(products)
} 





async function makeRequest(url, requestMethod, body) {
    try {
    const response = await fetch(url, {
        method: requestMethod,
        body
    })

    return response.json()

} catch(err) {
    console.error(err)
    }
}
