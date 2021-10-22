// Dépendances
import Form from './class/Form.js';
import * as callBack from "./ajaxCallBacks.js";

// Instantiation class Form
const formCtrl = new Form

// ******************** EVENT LISTEN ON PAGE ***************************
document.addEventListener("DOMContentLoaded", function(){
  
  
  // ******** CONTROLE INSCRIPTION FIELDS ********
  const isFormRegister = !!document.querySelector('#inscribe-form')
  
  // Check if element existed on the DOM
  if(isFormRegister){
    
    document.querySelector('#inscribe-form').addEventListener('submit', (event) =>{
    
      let formSelect = 'inscribe-form';
      
      getIn(formSelect)
    })
    
  }
  
  
  // ******** CONTROLE CONNECTION FIELDS ********
  const isFormLogin = !!document.querySelector('#login-formulaire')
  
  if(isFormLogin){
    
    document.querySelector('#login-formulaire').addEventListener('submit', (event) => {
    
      let formSelect = 'login-formulaire';
      
      getIn(formSelect)
      
    })
    
  }
  
  
  // *********** SEARCH BAR PAGE CARD ***********
  const inputSearch = document.querySelector('#searchInput')
  
  if(!!inputSearch){
    
    inputSearch.addEventListener('keyup', function (event) {
      
      let value = inputSearch.value
      
      if(value.length > 2) {
        callBack.search(value)
      }
      
    });
    
  }
  

  
  // *************** FORM MESSAGES CONTAIN CHATBOX ***************
  const chatBox = document.querySelector('#chat-discussion')
  const idReceiver = document.querySelector('#id-receiver')
 
  
  if(!!chatBox && !!idReceiver) {
    
    // callback to display messages between user and contact
    callBack.getChat()


    // To update messages
    setInterval(() =>{
      callBack.getChat()
    }, 5000)
    
    // get id of contact that user send message to
    const contactId = idReceiver.value
    
    // listen to submit event to call function to insert a new message in DB
    chatBox.addEventListener('submit', (event) => {
      insertMsg(contactId)
    })
    
    // listen to event of the keyboard 'enter' to call function to insert a new message in DB
    chatBox.addEventListener('keydown', (event) => {
      
      if(event.keyCode == 13) {
        insertMsg(contactId)
      }
      
    })
    
  }
  
  
})



/**
* Function to insert new message in DB
* @parameter one int => id of contact the message is address to
* @return void
*/
function insertMsg(contactId) {
  
  event.preventDefault()
      
  const textarea = document.querySelector('#chat-discussion textarea')
  
  const message = textarea.value
  
  if(message.trim()){
  
    const formMessage = new FormData()
    
    formMessage.append('action', 'chatBox')
    formMessage.append('receiver', contactId)
    formMessage.append('message', message )
    
    callBack.sendMsg(formMessage)
  
  }
  
}


/**
  - Asynchron function call to check inputs
  - Instantiate Form class
  - Even on the form register or connection
  - Manage display errors
  - Send new contacts in ajax to register new user or connecte user
*/
async function getIn(formId) {
    
  // Stop refresh
  event.preventDefault()
  console.clear()
  
  // recovery of all fields
  const $inputs = document.querySelectorAll(`#${formId} input`)

  // Inputs send for fields controle 
  formCtrl.fields = $inputs

  // erase the olds errors messages
  formCtrl.error.errors = []

  // Select form inscription or connection
  let form = document.querySelector(`#${formId}`)
  
  let result = await formCtrl.validate()
      
  if (!result) {
    
    // Manage Errors
    formCtrl.error.displayErrors()
    
  }else{
    
    // control structure to differentiate forms inscribe and login
    if($inputs.length > 4){
      
      // Send form inscribe
      const formRegister = new FormData(form)
      callBack.insert(formRegister)

    }else{
      
      // Send form login
      const formAuth = new FormData(form)
      callBack.auth(formAuth)
    }
      
  }
          
}
  