
<template>
  <div class="LoginWindow">
   <h1 id="header">ČUHANI</h1>  
   <form @submit.prevent="login">
    <div id="input-container">
        <input type="email" placeholder="E-MAIL" id="email" v-model="email" required>
        <input type="password" placeholder="PASSWORD" id="password" v-model="password" required>
        <RouterLink to="/ForgotPass" id="ForgotPass">Forgot password</RouterLink>
    </div>
    <button type="submit" id="login-but">LOGIN</button>
    <p>Dont have an account?</p>
    <RouterLink to="/register" id="register">Register</RouterLink>
   </form> 
  </div>
</template>

<script>
import axios from 'axios';

export default{
  data(){
    return{
      email: '',
      password:'',
    };
  },
    methods:{
      
      async login(){
        try{
          const response = await axios.post(`/login`,{
            email:this.email, 
            password:this.password
          });
          console.log(response.data);
          if(response.status === 200){
            if(response.data.status){
              localStorage.setItem('authToken', response.data.token);
              axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
              this.$router.push('/Home');
            }else{
              console.error('login failed', response.data.message);
              alert('error login failed');
            }
          }else{
            console.error('unexpected response status', response.status);
            alert('unexpected response status');
          }
        }
        catch(error){
          console.error(error.response.data);
          alert('error during login');
        }
        
      },
    },
  };

</script>

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

#input-container {
  width: 300px;
  height: 240px;
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
  margin-top: 0;
}

#password {
  margin-top: 20px;
}

#ForgotPass {
  margin-left: 10px;
  font: "Inter";
  font-size: 13px;
  color: var(--color-underline-gray);
}

#login-but {
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

#login-but:hover {
  transform: scale(1.10);
  border: 1px white solid;
}

#home {
  text-decoration: none;
}

p {
  top: 23px;
  left: 145px;
  font-size: 16px;
  display: flex;
  position: relative;
}

#register {
  left: 195px;
  top: 25px;
  font-size: 18px;
  display: flex;
  position: relative;
  color: var(--color-text);
}


</style>
