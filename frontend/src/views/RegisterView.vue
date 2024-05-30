<template>
  <div class="RegWindow">
    <h1 id="header">ČUHANI</h1>  
    <form @submit.prevent="register">
      <div id="input-container">
        <input type="email" placeholder="E-mail" v-model="formData.email" required>
        <input type="text" placeholder="Username" v-model="formData.username" required>
        <input type="text" placeholder="Name" v-model="formData.name" required>
        <input type="password" placeholder="Password" v-model="formData.password" required>
        <input type="password" placeholder="Confirm password" v-model="formData.password_confirmation" required>
      </div>
      <button type="submit" id="reg-but">REGISTER</button>
      <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
    </form> 
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      formData: {
        email: "",
        username:"",
        name: "",
        password: "",
        password_confirmation: ""
      },
      errorMessage: ""
    };
  },
  methods: {
    async register() {
      try {
        const response = await axios.post('/api/register', this.formData);
        console.log(response.data);
        alert("Registration successful!");
        // Optionally, you can redirect to another route upon successful registration
        this.$router.push("/");
      } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
          this.errorMessage = Object.values(error.response.data.errors)[0][0];
        } else {
          this.errorMessage = "Registration failed! Please try again later.";
        }
        console.error(error.response.data);
        alert(this.errorMessage);
      }
    }
  }
};
</script>

<style scoped>
.RegWindow {
  width: 450px;
  height: 600px;
  background-color: var(--color-element);
  border-radius: 25px;
  box-shadow: 0px 0px 300px 50px var(--color-element);
  display:block;
  padding: 20px;
  box-sizing: border-box;
}

#header {
  font:"Inter";
  font-size: 70px;
  font-weight: bold;
  display:flex;
  position:relative;
  left: 69px;
  top: 10px;
  width:300px;
  margin:0;
  padding:0;
}

#input-container {
  width: 300px;
  height: 240px;
  display: block;
  position: relative;
  align-items: center;
  left:3vw;

}

input {
  height: 70px;
  width: 300px;
  border-radius: 25px;
  border: 0;
  font-size: 20px;
  text-indent: 20px;
  margin-top: 1vh;
  font: "Inter";
  color: var(--color);
  background-color: var(--color-light-dark-red);
  position: relative;
  align-items: center;
}

#reg-but {
  height: 45px;
  width: 150px;
  border: none;
  border-radius: 25px;
  display: flex;
  position: relative;
  left: 6.5vw;
  top: 18vh;
  display: table-cell;
  vertical-align: middle;
  background-color: var(--color-red);
  color: var(--color-text);
  font: "Inter";
  font-size: 18px;
}

#reg-but:hover{
  transform: scale(1.10);
  border: 1px white solid;
}

.error-message {
  color: red;
  margin-top: 10px;
}
</style>
