 setInterval(() => {
     var inputField = document.querySelector('input[name="newtask"]');
     var placeholders = ["'Task 1'", "'Task 2'", "'Task 3'"];
     var randomIndex = Math.floor(Math.random() * placeholders.length);
     inputField.placeholder = placeholders[randomIndex];
 }, 3000);