import FormError from './FormError.js'

/**
  Class to verify entry in fields
  2 property
  5 functions
*/
class Form {

  constructor() {
    this._fields = []
    this._error = new FormError
  }
  
  // to get field value
  get fields() {
    return this._fields
  }
  
  // to change field value
  set fields(fields) {
    this._fields = fields
  }
  
  // to get error
  get error() {
    return this._error
  }
  

  
  /**
   * Check is user already register in database
   * @parameter one string
   * @returns {boolean} if user exist return true if user dont exist return false
   */
  async userExist(email) {
    
    let response = await fetch('src/services/ajax.php?searchEmail=' + email)
    
    if(response.ok){
      
      let data = await response.text()
      
      if(data){
        
        // if email already register in DB
        return false
        
      }else return true
      
    }else{
      console.error('Retour du serveur : ', response.statut)
    }
  
  }
  


  /**
   * Validation form method check inscription and connection form
   * @returns {boolean} if there are in error return false if no error return true
   */
  async validate() {

    // store contact if there are no error
    let contact = { user_name: '', first_name: '', last_name: '', email: '', password: '', confirm_password: '', login_email: '', login_password: '' }
    
    for (let field of this._fields) {
      
      if (!field.value) {
      
        // managing errors with FormError class
        this._error.addError({
          'name': field.id,
          'message': 'Ce champ est requis'
        })
        
      }else{
          
        let reg

        switch (field.dataset.type) {
            
          case "user_name":
            
            // required regex
            reg = /^[A-Za-z]+[0-9]+[^\W]+$/;
            
            if (!reg.test(field.value) || field.value.length < 7 ) {
                
              this._error.addError({
                'name': field.id,
                'message': 'Ce champ doit contenir minimum 7 caractères dont au moins un chiffre et pas de caractères speciaux'
              })
            } else contact.user_name = field.value
            
            break;
            
          case "gender":
            
            if (!field.checked) {
              
              this._error.addError({
                'name': field.id,
                'message': 'Ce champs est obligatoire'
              })
            } else contact.gender = field.value
            
            break;
            
          case "first_name":
            
            // required regex
            reg = /^[A-Za-z]+$/;
            
            if (!reg.test(field.value)) {
                
              this._error.addError({
                'name': field.id,
                'message': 'Ce champ ne peux pas contenir ni chiffres ni caractère spécial'
              })
            } else contact.first_name = field.value
            
            break;
        
          case "last_name":
            
            // required regex
            reg = /^[A-Za-z]+$/;
            
            if (!reg.test(field.value)) {
                
              this._error.addError({
                'name': field.id,
                'message': 'Ce champ ne peux contenir ni chiffre ni de caractère spécial'
              })
            } else contact.last_name = field.value
            
            break;
            
          case "email":
            
            let user = await this.userExist(field.value)
            
            // required regex
            reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            
            if (!reg.test(field.value)) {
              
              this._error.addError({
                'name': field.id,
                'message': 'Format email non valide.'
              })
              
            }
            if(!user){
              this._error.addError({
                'name': field.id,
                'message': 'Ce compte existe déja veuillez vous connecter'
              })
              
            }else{
              contact.email = field.value
            }
              
            break;
            
          case "password":
            
            // required regex
            reg = /(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*\W).{8,12}$/
            
            // special character not authorize
            const reg_not_valid = /[><]+/g
            
            if (!reg.test(field.value) ) {
              this._error.addError({
                'name': field.id,
                'message': 'Le mot de passe doit contenir entre 8 et 12 caractères dont une majuscule, une minuscule, un nombre et un caractère spécial'
              })
            
            }else if (reg_not_valid.test(field.value)) {
              this._error.addError({
                'name': field.id,
                'message': 'Ces caractères <> ne sont pas autorisés'
              })
              
            }else{
              contact.password = field.value
            }
            break;
            
          case "confirm_password":
            
            if ( contact.password != '' && contact.password !== field.value ) {
              this._error.addError({
                'name': field.id,
                'message' : 'Les deux mot de passe saisis ne correspondent pas'
              })
            } else contact.confirm_password = field.value
            
            break;
            
          case "login_email":
            
            reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            
            if (!reg.test(field.value)) {
              
              this._error.addError({
                'name': field.id,
                'message': 'Format email non invalide.'
              })
              
            }else{
              contact.login_email = field.value
            }
            break;
            
          case "login_password":
            
            contact.login_password = field.value
            
            break;
        }
      }
    }
    //check if they're any error
    if (this._error.errors.length != 0) {
      
      return false
      
    }else{
      return true;
    }
    
  }
   
  
  
}

export default Form