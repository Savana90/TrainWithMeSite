/**
  Class to manageand diplay errors in DOM
  4 functions
  
*/

class FormError {
    
  constructor() {
    this._errors = []
  }


  /**
    Function to add errors in array error
    @parameter one
    @return void
  */
  addError(error) {
    this._errors.push(error)
  }


  /**
    Function to get all errors 
    @parameter none
    @return @array
  */
  get errors() {
    return this._errors
  }


  /**
    Function to modify variable errors
    @parameter one
    @return void
  */
  set errors(error) {
    this._errors = error
  }


  /**
   * Function to add elements in the dom to show errors
   * 
   * @return void
  */
  displayErrors() {
    
    for (let i= 0; i < this.errors.length; i++) {
      
      const $span = document.createElement('span')
      // insert error message in new element span
      $span.innerText = this.errors[i].message
      $span.classList.add('form-error')
      
      const $input = document.querySelector(`#${this.errors[i].name}`)
      
      $input.parentNode.insertBefore($span, $input.nextSibling)
      
      // to remove message error after 5sec
      window.setTimeout(() =>{
        $span.remove()
      }, 5000)

    }
  }

  
  
  
  
  
}

export default FormError