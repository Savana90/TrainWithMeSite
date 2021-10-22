// Imports functions
import Form from './class/Form.js';

// Instantiation Form class
let errorInput = new Form


/**
 * Insert user
 * @param {FormRegister} form
 */
function insert(formRegister) {
  
  errorInput.error.errors = []

  // Request Ajax POST
  fetch(
    "src/services/ajax.php",
    {
      method: 'post',
      body: formRegister,
      data: formRegister
    }
  )
    .then(response => response.text())
    .then(msg => {
      
      if(msg == 'ok'){
        
        // Clean form
        document.querySelector('#inscribe-form').reset()
        let page = 'home'
        
        // call to function to relocate to home page
        relocation(page)
       
      }else{
        
        // add error in function addError class FormError
        errorInput.error.addError({
          'name': 'legend',
          'message': msg
        })
        // display error
        errorInput.error.displayErrors()
      }


    })
}


/**
 * Function to authentify user
 * @param {FormAuth}
 * 
 */
function auth(formAuth) {
  
  errorInput.error.errors = []
  
  // Request Ajax POST
  fetch(
    "src/services/ajax.php",
    {
      method: 'post',
      body: formAuth,
      data: formAuth 
    }
  )
    .then(response => response.text())
    .then(msg => {
        
      let page;
      
      if(msg == 'erreur'){
        
        // add message id formError
        errorInput.error.addError({
          'name': 'legend',
          'message': 'Mot de passe ou email incorrect'
        })
        // display error on active page
        errorInput.error.displayErrors()
        
      }else if(msg == 'connecté'){
        
        // add message id formError
        errorInput.error.addError({
          'name': 'legend',
          'message': 'Une session avec vos données de connection est déjà en cours, veuillez vous déconnectez de celle ci avant de vous reconnecter'
        })
        // display error on active page
        errorInput.error.displayErrors()
        
      }else if(msg == 'home' ){
        
        // Clean form
        document.querySelector('#login-formulaire').reset()
        page = 'home'
        
        // call to function to relocate to home page
        relocation(page)
      
      }else if(msg == 'admin'){
        
        // Clean form
        document.querySelector('#login-formulaire').reset()
        page = 'admin'
        
        // call to function to relocate to admin page
        relocation(page)
          
      }
    })
}


/**
 * Function to fetch value from the search bar and find a user 
 * @param value of the search bar
 * 
 */
function search(value) {
  
  // select div to add results
  const result = document.querySelector('#resultSearch');
  result.textContent = ''
  
  // erase old results
  if(document.querySelector('.new-ul')){
    result.removeChild(document.querySelector('.new-ul'))
  }
  // Creation element ul and add class to it
  let $newUl = document.createElement("ul");
  $newUl.classList.add('new-ul')

  // Request Ajax GET
  fetch(
    "src/services/ajax.php?searchUser=" + value
  )
    .then(response => response.json())
    .then(msg => {
      console.log(msg)
      for(let i = 0; i < msg.length; i++) {
        
        // create element 'li' and 'a'
        let $newLi = document.createElement("li")
        let $newLink = document.createElement("a")
        
        // add href attribut to element a
        $newLink.setAttribute('href', `index.php?template=card&profil=${msg[i].id}`)
        
        // add fetch results in 'a' element
        $newLink.innerText = msg[i].first_name + ' ' + msg[i].last_name
        
        // append element 'a' to 'li'
        $newLi.appendChild($newLink)
        // append element 'li' to 'ul'
        $newUl.appendChild($newLi)
        // append element 'ul' to 'div'
        result.appendChild($newUl)
      }
      
      // if msg is empty no result find
      if(msg.length === 0){
        result.textContent = 'Aucun résultat trouvé'
      }
      
    })
}


/**
 * function to relocate page on home or admin page if all the verification is ok
 * @parameter 1 string
 * @return void
 * 
 */
function relocation(page) {
  if(!!document.querySelector('.spinner')){
    document.querySelector('.spinner').style.opacity = 1
  }
  
  setTimeout(() =>{
    window.location.href = `../../PROJET3WA/index.php?template=${page}`
  }, 4000)
}

/**
 * Function to fetch messages send by user and insert it in to DB then display all messages
 * @parameter formMessage
 */
function sendMsg(formMessage) {
 
  // Request Ajax POST
  fetch(
    "src/services/ajax.php",
    {
      method: 'post',
      body: formMessage,
      data: formMessage
    }
  )
    .then(response => response.text())
    .then(msg => {
      
      if(msg == 'ok') {
        
        // Clean form
        document.querySelector('#chat-discussion').reset()
        
        // call function divDiscussion so the recent messages send can show up
        getChat()
      } else if(msg == 'vide') {
        
      }
      
    })
}

/**
 * Function fetch to recover all message exchange between user and contact
 */
function getChat() {
  
  // select input to get value that is the contact Id
  const idContact = document.querySelector('#id-receiver').value
  let divChat = document.querySelector('#chat-div')

  // Request Ajax GET => send id contact
  fetch(
    "src/services/ajax.php?id=" + idContact,
   
  )
    .then(response => response.json())
    .then(result => {
      
      // erase old results
      if(!!document.querySelector('.sender') || !!document.querySelector('.receiver') ){
        
        divChat.innerHTML= ''
      }
      
      for(let i = 0; i < result.length; i++ ) {
        
        // to maintain the scrollbar at the bottom so the last messages is visible
        divChat.scrollTop = divChat.scrollHeight
        
        if( result[i].receiver_foreign_key == idContact ) {
          
          // create element p and add messages of the user
          let $newP = document.createElement("p")
          $newP.setAttribute('class', 'sender')
          $newP.innerHTML = `${result[i].content} <i class="fas fa-user-circle"></i>`
          
          // create element span and add date message has been sent
          let $newSpan = document.createElement("span")
          $newSpan.textContent = result[i].date_cast
        
          // append element p to the divchat and element span to element p
          divChat.appendChild($newP)
          $newP.appendChild($newSpan)
          
          // to maintain de scrollbar at the bottom so the last messages are visible
          divChat.scrollTop = divChat.scrollHeight
          
        }else{
          
          // create element p and add the msg of contact
          let $newPbis = document.createElement("p")
          $newPbis.setAttribute('class', 'receiver')
          $newPbis.innerHTML = `<i class="fas fa-user-circle"></i> ${result[i].content}`
          
          // create element span and add date the msg has been sent
          let $newSpan = document.createElement("span")
          $newSpan.textContent = result[i].date_cast
          
          // append element p to the divchat and element span to element p
          divChat.appendChild($newPbis)
          $newPbis.appendChild($newSpan)
          
          // to maintain the scrollbar at the bottom so the last messages is visible
          divChat.scrollTop = divChat.scrollHeight
         
        }
      }
      
    })
}



export { insert , auth , search , sendMsg , getChat }

