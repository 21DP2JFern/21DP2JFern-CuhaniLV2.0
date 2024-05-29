<template>
    <Header />
    <div id="profile-box">
        <div id="profile-info-box">
            <div id="profile-pic-container">
                <input type="file">
            </div>
            <button id="save-but" @click="updateProfile">Save changes</button>
            <button id="delete-acc-but" @click="deleteProfile">Delete account</button>
            <input type="text" id="change_username" :value="profileData.username">
            <textarea id="biobox" rows="5" cols="50">{{ profileData.bio }}</textarea>
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
    
                updateProfile() {
                    // Retrieve authToken from local storage
                    const authToken = localStorage.getItem('authToken');
    
                    // Check if authToken exists before proceeding
                    if (!authToken) {
                        console.error('Authentication token not found.');
                        return;
                    }
    
                    // Get the bio text from the textarea
                    const bioText = document.getElementById('biobox').value;
                    const changeUsernameText = document.getElementById('change_username').value;

    
                    axios.post('/updateProfile', {
                        bio: bioText,
                        username: changeUsernameText

                    }, {
                        headers: {
                            Authorization: `Bearer ${authToken}`
                        }
                    })
                    .then(response => {
                        alert("Changes saved");
                        this.$router.push('/profile');
                    })
                    .catch(error => {
                        console.error(error);
                    });
                },

                deleteProfile(){
                    const authToken = localStorage.getItem('authToken');
                    localStorage.removeItem('authToken');
                    axios.delete('http://127.0.0.1:8000/api/deleteProfile').then(response => {
                        console.log(response.data.message);
                        alert('Account deleted succesfully');
                        this.$router.push('/login');
                    }).catch(error => {
                        console.error('Error deleting profile:', error);
                        alert('Problem deleting profile');
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
    
    #save-but{
      height:40px;
      width: 150px;
      border: none;
      border-radius: 25px;
      display:flex;
      position:relative;
      top: 40px;
      left: 60px;
      display: table-cell;
      vertical-align: middle;
      background-color: var(--color-red);
      color: var(--color-text);
      font: "Inter";
      font-size: 16px;
      font-weight: 550;
    }

    #save-but:hover{
        transform: scale(1.10);
        border: 1px white solid;
    }
    
    #delete-acc-but{
        height:40px;
        width: 150px;
        border: none;
        border-radius: 25px;
        display:flex;
        position:relative;
        top: 90px;
        left: -90px;
        display: table-cell;
        vertical-align: middle;
        background-color: #990d03;
        color: var(--color-text);
        font: "Inter";
        font-size: 16px;
        font-weight: 550;
    }

    #delete-acc-but:hover{
        transform: scale(1.10);
        border: 1px white solid;
    }

    #change_username{
        height: 50px;
        width: 200px;
        border-radius: 25px;
        border: 0;
        font-size: 20px;
        font: "Inter";
        color: var(--color-text);
        background-color: var(--color-light-dark-red);
        position: relative;
        margin-top:-200px;
        left: 260px;
        text-indent: 20px;
        display:flex;
    }

    
    
    #biobox{
        position:relative;
        font-size: 18px;
        margin-top:10px;
        left: 260px;
        border-radius: 25px;
        border: 0;
        font-size: 20px;
        color: var(--color-text);
        background-color: var(--color-light-dark-red);
        padding: 5px;
        text-indent: 10px;
        font: "Inter";
    }
    
    #savedBio{
        font: "inter";
        position:relative;
        font-size: 35px;
        margin-top:0px;
        left: 280px;
    }
    
    
    
    </style>
    
    