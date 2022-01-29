import Component from 'flarum/common/Component';

export default class SiteSplash extends Component {
  oninit(vnode) {
    super.oninit(vnode);
  }

  view() { 
    // we already hassled them, show noting
    // **NOTE:** I use session storage as it may be a shared computer, clear when page closes
    if ( app.session.user || sessionStorage.getItem('ageVerify') == 'true') {
        return "";
    }
     
    if( ! this.attrs.enterText ) this.attrs.enterText = app.forum.attribute("age_yes")
    if( ! this.attrs.exitText ) this.attrs.exitText = app.forum.attribute("age_no")
    if( ! this.attrs.verifyPrompt ) this.attrs.verifyPrompt = app.forum.attribute("age_prompt")
    
    //we don't know them, prompt with screenblock (from https://codepen.io/amirnaeem/pen/GZmXwr)
    $('.thegoods').addClass("blur")
    return (
      <div class="nonobox">
          <div class="verifybox">
              <div class="verifybox-left" style={{backgroundImage: "url(" + app.forum.attribute('age_image') + ")",backgroundPosition: "50% 50%"}}>
                  <p> {app.forum.attribute('age_message')} </p>
              </div>
              <div class="verifybox-right">
                  <h3>Age Verification</h3>
                  <p>{this.attrs.verifyPrompt}</p>
                  <button id='consent-enter' class="btn btn-alpha" 
                      onclick={(e)=>{
                          $('.thegoods').removeClass('blur')
                          $('.box').hide();
                          sessionStorage.setItem('ageVerify','true');
                          }} >
                      { this.attrs.enterText}
                  </button>
                  
                  <p class="decor-line"><span>Or</span></p>

                  <button class="btn btn-beta" id="consent-exit" 
                      onclick={(e)=>{
                          window.history.back()
                          }} >
                      {this.attrs.exitText}
                  </button>
                  
                  <small>Always enjoy responsibily.</small>
              </div>
          </div>
      </div>
    );
  }

  oncreate(vnode) {
    super.oncreate(vnode);
  }
}
