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
        const response = await axios.post('http://127.0.0.1:8000/api/register', this.formData);
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
  font-size: 48px;
  font-weight: bold;
  margin-bottom: 20px;
}

#input-container {
  margin-bottom: 20px;
}

input {
  width: 100%;
  height: 40px;
  margin-bottom: 10px;
  padding: 0 10px;
  font-size: 16px;
}

#reg-but {
  width: 100%;
  height: 40px;
  background-color: var(--color-red);
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.error-message {
  color: red;
  margin-top: 10px;
}
</style>
