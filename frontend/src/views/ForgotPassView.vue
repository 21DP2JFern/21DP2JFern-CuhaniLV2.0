
<template>
  <div class="LoginWindow">
   <h1 id="header">ČUHANI</h1>  
   <form>

    <p id="ForgotPassMessage">Enter your email address and we'll send you a link to reset your password</p>
    <div id="input-container">
        <input type="text" placeholder="E-MAIL" id="email">
    </div>
    <RouterLink to="/ResetPass" id="send">
      <button id="send-but">Send</button>
    </RouterLink>
    <p id="or">Or</p>
    <RouterLink to="/register" id="register">Create new account</RouterLink>
   </form> 
  </div>
</template>

<style scoped>
body{
  place-items: center;
  overflow:auto;
}

.LoginWindow {
  width: 450px;
  height: 600px;
  background-color: var(--color-element);
  border-radius: 25px;
  box-shadow: 0px 0px 300px 50px var(--color-element);
  display:block;
}

#header{
  font:"Inter";
  font-size: 80px;
  font-weight: bold;
  display:flex;
  position:relative;
  left: 69px;
  top: 40px;
  width:300px;
  margin:0;
  padding:0;
}

p {
  width: 16vw;
  font-size: 16px;
  display: flex;
  position: relative;
  text-align: center;
}

#ForgotPassMessage{
  top: 7vh;
  left: 4vw;
}

#input-container {
  width: 300px;
  height: 180px;
  display: block;
  position: relative;
  top: 45px;
  left: 77px;
}

input {
  height: 70px;
  width: 300px;
  border-radius: 25px;
  border: 0;
  font-size: 20px;
  text-indent: 20px;
  font: "Inter";
  color: var(--color-text);
  background-color: var(--color-light-dark-red);
}

input::placeholder {
  color: var(--color-text);
  font-size: 17px;
}

#email {
  margin-top: 45px;
}

#send-but {
  height: 45px;
  width: 150px;
  border: none;
  border-radius: 25px;
  display: flex;
  position: relative;
  left: 150px;
  display: table-cell;
  vertical-align: middle;
  background-color: var(--color-red);
  color: var(--color-text);
  font: "Inter";
  font-size: 18px;
}

#send-but:hover {
  transform: scale(1.10);
  border: 1px white solid;
}

#send {
  text-decoration: none;
}

#or{
  margin-top: 2vh;
  left: 11.2vw;
}

#register {
  left: 7.7vw;
  top: 2vh;
  font-size: 18px;
  display: flex;
  position: relative;
  color: var(--color-text);
}


</style>
