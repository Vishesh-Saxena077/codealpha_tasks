 async function fetchBooks(query) {
     try {
         const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=${query}&maxResults=20`);

         if (response.ok) {
             const data = await response.json();
             console.log("Success");
             console.log(data);

             const bookDisplaySection = document.getElementById('bookDisplay');
             bookDisplaySection.innerHTML = ''; // Clear previous search results

             data.items.forEach(book => {
                 const bookCard = createBookCard(book.volumeInfo);
                 bookDisplaySection.appendChild(bookCard);
             });
         } else {
             console.log("Request failed with status:", response.status);
             console.log("Error:", response.statusText);
         }
     } catch (error) {
         console.log("Error:", error.message);
     }
 }

 function createBookCard(bookInfo) {
     var card = document.createElement('div'); // Create a <div> element for the book card
     card.className = 'book-card'; // Set the class attribute to 'book-card'

     var title = document.createElement('h2'); // Create an <h2> element for the book title
     title.textContent = bookInfo.title; // Set the text content to the book's title

     var thumbnail = document.createElement('img'); // Create an <img> element for the book's thumbnail image
     thumbnail.src = bookInfo.imageLinks && bookInfo.imageLinks.thumbnail ? bookInfo.imageLinks.thumbnail : 'https://via.placeholder.com/150'; // Set the 'src' attribute to the book's thumbnail URL if available; otherwise, use a placeholder image
     thumbnail.alt = 'Product Image';

     var authors = document.createElement('p'); // Create a <p> element for the book authors
     authors.textContent = 'Authors: ' + (bookInfo.authors ? bookInfo.authors.join(', ') : 'N/A'); // Set the text content to the book's authors or display 'N/A' if not available

     var description = document.createElement('p'); // Create a <p> element for the book description
     description.textContent = bookInfo.description || 'No description available'; // Set the text content to the book's description or display a default message if not available

     var viewButton = document.createElement('button');
     viewButton.textContent = 'View';
     viewButton.onclick = function() {
         handleViewButtonClick(bookInfo);
     };

     // Append the created elements to the book card
     card.appendChild(thumbnail); // Append the description to the card
     card.appendChild(title); // Append the title to the card
     card.appendChild(authors); // Append the authors to the card
     card.appendChild(description); // Append the description to the card
     card.appendChild(viewButton); // Append the View button to the card


     return card; // Return the constructed book card element
 }

 fetchBooks(); // Call the fetchBooks function to initiate the fetching and displaying of book data

 function performSearch() {
     const searchTerm = document.getElementById('searchInput').value.trim();
     const categorySelect = document.getElementById('categorySelect').value;
     if (searchTerm !== '') {
         fetchBooks(searchTerm);
     } else if (categorySelect !== '') {
         fetchBooks(categorySelect);
     } else {
         console.log("Please enter a search term");
     }
 }


 // Function to handle clicking on book cards and storing history
 function handleViewButtonClick(bookInfo) {
     // Store the clicked book's information in localStorage
     const history = JSON.parse(localStorage.getItem('bookHistory')) || [];
     history.push(bookInfo);
     localStorage.setItem('bookHistory', JSON.stringify(history));

     // Redirect to the history page to view the browsing history
     //  window.location.href = 'history.html';
 }