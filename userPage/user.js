function search(){
    let searchBar = document.getElementById('search').value.toUpperCase();
    const cardContainer = document.querySelector('.card-container');
    const cards = document.querySelectorAll('.product-card');
    const noContent = document.querySelector('.no-card-container');
    let visibleCardCount = 0;

    for(let i = 0; i<cards.length;i++){
        let cardTitle = cards[i].querySelector('.productName');
        let cardIsVisible = false;
        
        if (cardTitle) {
            if (cardTitle.innerHTML.toUpperCase().indexOf(searchBar) >= 0) {
                cards[i].style.display = "";
                cardIsVisible = true;
            } else {
                cards[i].style.display = "none";
            }
        }
        
        if (cardIsVisible) {
            visibleCardCount++;
        }
    }

    if (visibleCardCount === 0) {
        noContent.style.display = "flex";
    } else {
        noContent.style.display = "none";
    }
}

function filter() {
    const filterSelect = document.getElementById('filter');
    const selectedCategory = filterSelect.options[filterSelect.selectedIndex].text.toUpperCase();
    
    const cards = document.querySelectorAll('.product-card');
    const noContent = document.querySelector('.no-card-container');
    let visibleCardCount = 0;

    cards.forEach(card => {
        const cardCategory = card.querySelector('.productCategory').textContent.toUpperCase();

        if (selectedCategory === "ALL" || selectedCategory === cardCategory) {
            card.style.display = "";
            visibleCardCount++;
        } else {
            card.style.display = "none";
        }
    });

    if (visibleCardCount === 0) {
        noContent.style.display = "flex"; 
    } else {
        noContent.style.display = "none";
    }
}