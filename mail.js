//script.js

//initialise emaijs with your pubic key
const emailjs = require('emailjs-com');
emailjs.init("qTAKMcdWEsdS68fQ6");

//attach even listener to the form submit event
document.getElementById('contact-form').addEventListener('submit', function(event) {
    event.preventDefault(); //prevent default form submission

    //collect form data
    const formData = {
        name: document.getElementById('FullName').value,
        email: document.getElementById('email').value,
        Phone: document.getElementById('phone_field').value,
   
    };
    //send email using emailjs
    emailjs.send('service_w7it231', 'template_rfidyql', formData)
        .then(function(response) {
            console.log('SUCCESS!', response.status, response.text);
            alert('Message sent successfully!');
        }, function(error) {
            console.log('FAILED...', error);
            alert('Failed to send message. Please try again later.');
        });
}); 
