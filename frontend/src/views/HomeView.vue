<template>
<Header />
<div id="container">
    <h1 id="welcome-text" v-if="profileData">Welcome, {{ profileData.username }}!</h1>
    <Line id="line" />
    <div id="friend-activity-container">
        <p id="friend-activity">Followed users activity</p>
        <div id="post-container">
        <ul class="post-list">
            <li v-for="(post, index) in posts" :key="index" class="post-box" @click="navigateToPost(post.id)"> 
                <p id="author-text">Autors: {{ post.autors }}</p>
                <h3 id="nosaukums">{{ post.nosaukums }}</h3>
                <p id="saturs">{{ post.saturs }}</p>
                <small id="date">Date: {{ post.date }}</small>
            </li>
        </ul>
    </div>
    </div>
</div>

</template>

<style>

#post-container{
    width:25vw;
    position:relative;
    height: 70vh;
    overflow-y: scroll;
    top:2vh;
    left:2vw
}

.posts-container {
    width: 100%;
    margin-top: 20px;
}

.post-list {
    padding: 0;
    list-style-type: none;
}

.post-box {
    border: none;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 25px;
    background-color: var(--color-light-dark-red);
    margin-top: 1vh;
}

#container{
    width: 60vw;
    height: 70vh;
    background-color: var(--color-element);
    align-self: center;
    border-radius: 25px;
}

#welcome-text{
    left:2vw;
    top: 2vh;
    position: relative;
    font-weight: 500;

}

#line{
    position: relative;
    top: 1vh;
    background-color: black;
}
#friend-activity-container{
    position: relative;
    width: 28vw;
    height: 70vh;
    left:2vw;
}
#friend-activity{
    position: relative;
    font-size: 25px;
    font-weight: 500;
    left: 2vw;
    top:1vw;
}
</style>

<script>
import Header from '../components/Header.vue'
import axios from 'axios'
import Line from '../components/line.vue'

export default{
    name: "Home",
    components:{
        Header,
        Line
    },
    data() {
        return {
            profileData: {},
            posts: [],
        };
    }, 
    mounted() {
        const authToken = localStorage.getItem('authToken');
        if(authToken) {
            this.fetchProfile(authToken);
            this.getForumPost();
        } else {
            this.$router.push('/login');
        }
    },
    methods:{
        fetchProfile(authToken){
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
            async getForumPost() {
            const token = localStorage.getItem('authToken'); 
            try {
                const response = await axios.get('/api/getAllFriendsForumPosts', {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                if (response.data && response.data.data) {
                    this.posts = response.data.data; 
                } else {
                    console.error('Unexpected response structure:', response.data);
                    alert('Error displaying forums: Unexpected response structure');
                }
            } catch (error) {
                console.error('Error fetching forum posts:', error);
                alert('Error displaying forums');
            }
        },
        },
}
</script>
