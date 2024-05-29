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
    <Line id="divider-line" />
    <h2 id="my-posts">My posts</h2>
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
</template>

<script>
import Header from '../components/Header.vue'
import Line from '../components/line.vue'
import axios from 'axios'

export default{
    name: "Home",
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
        
        async getForumPost() {
            const token = localStorage.getItem('authToken'); 
            try {
                const response = await axios.get('/getAllUsersForumPosts', {
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
        async navigateToPost(id) {
            this.$router.push({ name: 'PostDetails', params: { postId: id } });
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
    left: 280px;
    margin-top: -27.5vh;

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

#divider-line{
    width: 40vw;
    height:0.2vh;
    left: 2.5vw;
    background-color: black;
    position: relative;
}

#my-posts{
    position:relative;
    font:'inter';
    font-weight: 500;
    left:2.5vw;
}

#post-container{
    width:40vw;
    left:2.5vw;
    position:relative;
    height: 40vh;
    overflow-y: scroll;
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

</style>

