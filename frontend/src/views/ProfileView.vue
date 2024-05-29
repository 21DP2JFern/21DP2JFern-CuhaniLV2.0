<template>
<Header />
<div id="profile-box">
    <div id="profile-info-box">
        <div id="profile-pic-container">
            <img src="../assets/profile-pic.png" id="profile-pic">
        </div>
        <RouterLink to="/EditProfileView">
            <button id="edit-but">Edit Profile</button>
        </RouterLink>
        <p id="username" v-if="profileData">{{ profileData.username }}</p>
        <p id="savedBio" v-if="profileData">{{ profileData.bio}}</p>
    </div>
    <Line id="Uline" />
    
</div>
</template>

<script>
import Header from '../components/Header.vue'
import Line from '../components/Line.vue'
import axios from 'axios'

export default{
    name: "Home",
    data() {
        return {
            profileData: {}

        };
    }, 
    mounted() {
        const authToken = localStorage.getItem('authToken');
        if(authToken) {
            this.fetchProfile(authToken);
        } else {
            this.$router.push('/login');
        }
    },
    methods:{
        fetchProfile(authToken){
                axios.get('/getUserProfile', {
                    headers: {
                        Authorization: `Bearer ${authToken}`
                    }
                }).then((response) => {
                    this.profileData = response.data.userData;
                    console.log("Profile Data:", this.profileData);

                }).catch((error) => {
                    console.log(error.message);
                });
            },
        },
    components:{
        Header,
        Line
    },
}
</script>

<style scoped>

#profile-box{
    background-color: var(--color-element);
    width: 45vw;
    height: 80vh;
    border-radius: 25px;
}

#profile-info-box{
    width:100%;
    height: 40%;
}

#profile-pic{
    height: 20vh;
    width: 11vw;
}
#profile-pic-container{
    position:relative;
    left: 30px;
    top: 30px;
    height: 20.5vh;
    width: 11.5vw;
    border: black 2px solid;
}

#edit-but{
  height: 45px;
  width: 150px;
  margin-top: 40px;
  border: none;
  border-radius: 25px;
  display: flex;
  position: relative;
  left: 65px;
  display: table-cell;
  vertical-align: middle;
  background-color: var(--color-red);
  color: var(--color-text);
  font: "Inter";
  font-size: 18px;
  text-decoration: none;
}

#edit-but:hover{
    transform: scale(1.10);
    border: 1px white solid;
}

#username{
    font: "inter";
    font-size: 40px;
    position:relative;
    margin-top:-255px;
    left: 280px;
}

#savedBio{
    font: "inter";
    position:relative;
    margin-top:0px;
    font-size: 25px;
    left: 280px;
    width: 500px;
    height: 200px;
    overflow-wrap: break-word;
    
}
#save-but{
  height:40px;
  width: 150px;
  border: none;
  border-radius: 25px;
  display:flex;
  position:relative;
  top: 40px;
  left: 100px;
  display: table-cell;
  vertical-align: middle;
  background-color: var(--color-red);
  color: var(--color-text);
  font: "Inter";
  font-size: 16px;
  font-weight: 550;
}



</style>

