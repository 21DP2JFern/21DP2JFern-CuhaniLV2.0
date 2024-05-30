<template>
    <div id="Header">
        <button id="menu-but" @click="openmenu()"><img src="../assets/menu.png" id="menu-img"></button>
        <RouterLink to="/home">
            <h1 id="cuhani-text">ČUHANI</h1>
        </RouterLink>
        <RouterLink to="/profile">
            <button id="profile-but">Your Profile</button>
        </RouterLink>
    </div>
    <div id="sidebar">
        <div class="home">
            <RouterLink to="/home">
                <button class="button">Home</button>
            </RouterLink>
        </div>
        <div class="news">
            <RouterLink to="/news">
                <button class="button">News</button>
            </RouterLink>
        </div>
        <div class="Forums">
            <RouterLink to="/forums_hub">
                <button class="button">Forums</button>
            </RouterLink>
        </div>
        <div class="Friends">
            <RouterLink to="/friends">
                <button class="button">Friends</button>
            </RouterLink>
        </div>
        <div class="log-out">
            <button class="button" @click="logout">Log Out</button>
        </div>
    </div>
</template>

<script>
import { RouterLink } from 'vue-router';
import axios from 'axios';

export default {
    name: "Header",
    data() {
        return {
            profileData: {}
        };
    },
    mounted() {
        const authToken = localStorage.getItem('authToken');
        if (authToken) {
            this.fetchProfile(authToken);
        } else {
            this.$router.push('/login');
        }
    },
    methods: {
        openmenu() {
            var x = document.getElementById("sidebar");
            var computedStyle = window.getComputedStyle(x);
            if (computedStyle.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        },
        fetchProfile(authToken) {
            axios.get('/api/getUserProfile', {
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
        logout() {
            localStorage.removeItem('authToken');
            this.$router.push('/login');
        }
    },
    components: { RouterLink },
};
</script>

<style scoped>


#Header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 5vh;
    background-color: var(--color-element);
    box-shadow: 0px 0px 100px 20px var(--color-element);
    text-align: center;
    z-index: 1000;
}

#cuhani-text {
    position: absolute; 
    top: 50%; 
    left: 50%; 
    transform: translate(-50%, -50%); 
    width: 150px;
    font: "Inter";
    color: var(--color-text);
    font-weight: 600;
    font-size: 3.5vh;
}

#menu-but {
    height: 5vh;
    width: 50px;
    padding: 0;
    background: transparent;
    border: 0;
}

#menu-img {
    height: 5vh;
    width: 50px;
    top: 0;
    left: 0;
    display: flex;
    position: absolute;
}

#profile-but {
    position: absolute;
    right: 1vw;
    top: 0;
    height: 5vh;
    background: transparent;
    border: none;
    color: white;
    max-width: 30ch;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: medium;

}

#sidebar {
    height: 520px;
    width: 300px;
    background-color: var(--color-element);
    z-index: 2;
    top: 5vh;
    left: 0;
    border-bottom-right-radius: 25px;
    position: absolute;
    display: none;
}

.button {
    height: 100px;
    width: 100%;
    position: relative;
    color: white;
    background-color: var(--color-element);
    padding: 10px;
    text-align: left;
    border: none;
    font: 'Inter';
    font-size: 18px;
    text-align: center;
}
</style>
