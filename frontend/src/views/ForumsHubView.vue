<template>
    <Header />
    <div id="forum-container">
        <div class="content-wrapper">
            <div class="header-section">
                <h1 id="forums-header">FORUMS</h1>
                <p>Total Posts: {{ postCount }}</p>
                <button @click="showModal = true" id="create-post-button">Create New Post</button>
            </div>
            <Line id="line"/>
            <div class="posts-container">
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
        <div v-if="showModal" class="modal">
            <div class="modal-content">
                <span class="close" @click="showModal = false">&times;</span>
                <form @submit.prevent="createNewPost">
                    <h2 id="create-post-header">CREATE NEW POST</h2>
                    <input id="post-title-input" type="text" v-model="newPostTitle" placeholder="Enter title" required>
                    <textarea id="post-content-input" v-model="newPostContent" placeholder="Enter content" required></textarea>
                    <button id="post-but" type="submit">Post</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '../components/Header.vue';
import Line from '../components/line.vue';
import axios from 'axios';

export default {
    name: "ForumsHubView",
    components: {
        Header,
        Line
    },
    data() {
        return {
            posts: [],
            newPostTitle: '',
            newPostContent: '',
            showModal: false,
            postCount: 0,
        };
    },
    methods: {
        async getForumPost() {
            const token = localStorage.getItem('authToken'); 
            try {
                const response = await axios.get('/api/getAllForumPosts', {
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
        async fetchProfile() {
            const token = localStorage.getItem('authToken'); 
            try {
                const response = await axios.get('/api/getUserProfile', {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                return response.data.userData;
            } catch (error) {
                console.error('Error fetching profile:', error);
                return null;
            }
        },
        async navigateToPost(id) {
            this.$router.push({ name: 'PostDetails', params: { postId: id } });
        },
        async createNewPost() {
            const token = localStorage.getItem('authToken');
            try {
                const profileData = await this.fetchProfile();
                if (profileData) {
                    const response = await axios.post('/api/createForumPost', {
                        nosaukums: this.newPostTitle,
                        saturs: this.newPostContent,
                        autors: profileData.id // Use the actual user ID from the profile data
                    }, {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    });
                    if (response.data && response.data.status) {
                        alert('Post created successfully');
                        this.newPostTitle = ''; // Clear the form fields
                        this.newPostContent = '';
                        this.showModal = false; // Close the modal
                        this.getForumPost(); // Refresh the posts list
                        window.location.reload();
                    } else {
                        alert('Error creating post');
                    }
                } else {
                    alert('Error fetching profile data');
                }
            } catch (error) {
                console.error('Error creating post:', error);
                alert('Error creating post');
            }

        },
        async getPostCount() {
        const token = localStorage.getItem('authToken');
        try {
            const response = await axios.get('/api/countForumPosts', {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            if (response.data && response.data.data !== undefined) {
                this.postCount = response.data.data;
            } else {
                console.error('Unexpected response structure:', response.data);
                alert('Error fetching post count: Unexpected response structure');
            }
        } catch (error) {
            console.error('Error fetching post count:', error);
            alert('Error fetching post count');
        }
    },
},
    mounted() {
        this.getForumPost();
       this.getPostCount();
    }
    
};
</script>

<style scoped>
Header{
    z-index: 999;
}
#forums-header{
    padding-right: 20px;
    font-weight: 500;
}
#forum-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 80vh;
   overflow-y: scroll;
   background-color: var(--color-element);
   margin-top: 10vh;
   border-radius: 25px;
}



.content-wrapper {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    box-sizing: border-box;
    text-align: center;
}

.header-section {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    padding: 0px;
    box-sizing: border-box;
    z-index: 10;
}



#line{
    margin-left: 0vw;
    background-color: black;
    width: 60vw;

}

#create-post-button {
    margin-left: auto;
    padding: 10px 20px;
    font-size: 16px;
    border:none;
    background-color: var(--color-red);
    color:white;
    border-radius: 25px;
    height:50px;
}
#create-post-button:hover{
    transform: scale(1.10);
    border: 1px white solid;
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
}


.modal {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    z-index: 100;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.6);
}

.modal-content {
    background-color:var(--color-element);
    margin: auto;
    padding: 25px;
    border: none;
    width: 40vw;
    height: 40vh;
    border-radius: 25px;
    
}

#post-title-input{
    height: 50px;
    width: 200px;
    border-radius: 25px;
    border: 0;
    font-size: 20px;
    font: "Inter";
    color: white;
    background-color: var(--color-light-dark-red);
    position: relative;
    text-indent: 20px;
    display:flex;
    margin-top: 10px;
}

#post-content-input{
    position:relative;
    font-size: 18px;
    width: 35vw;
    height:20vh;
    margin-top:10px;
    border-radius: 25px;
    border: 0;
    font-size: 20px;
    color: var(--color-text);
    background-color: var(--color-light-dark-red);
    padding: 5px;
    text-indent: 10px;
    font: "Inter";
}

#post-but{
    height: 45px;
    width: 150px;
    border: none;
    border-radius: 25px;
    display: flex;
    position: relative;
    display: table-cell;
    vertical-align: middle;
    background-color: var(--color-red);
    color: var(--color-text);
    font: "Inter";
    font-size: 18px;
    text-decoration: none;
}

#post-but:hover{
    transform: scale(1.10);
    border: 1px white solid;
}

#author-text{
    text-align: left;
}

#nosaukums{
    text-align: left;
}

#saturs{
    text-align: left;
}

#date{
    text-align: right;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    margin-top: -1.5vh;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
</style>
