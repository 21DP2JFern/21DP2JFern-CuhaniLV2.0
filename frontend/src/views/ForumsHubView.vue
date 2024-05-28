<template>
    <Header />
    <div id="forum-container">
        <div class="content-wrapper">
            <div class="header-section">
                <h1 id="forums-header">FORUMS</h1>
                <button @click="showModal = true" class="create-post-button">Create New Post</button>
            </div>
            <Line id="line"/>
            <div class="posts-container">
                <ul class="post-list">
                    <li v-for="(post, index) in posts" :key="index" class="post-box" @click="navigateToPost(post.id)"> 
                        <h3>{{ post.nosaukums }}</h3>
                        <p>{{ post.saturs }}</p>
                        <p>Autors: {{ post.autors }}</p>
                        <small>Date: {{ post.date }}</small>
                    </li>
                </ul>
            </div>
        </div>
        <div v-if="showModal" class="modal">
            <div class="modal-content">
                <span class="close" @click="showModal = false">&times;</span>
                <form @submit.prevent="createNewPost">
                    <input type="text" v-model="newPostTitle" placeholder="Enter title" required>
                    <textarea v-model="newPostContent" placeholder="Enter content" required></textarea>
                    <button type="submit">Publish</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '../components/Header.vue';
import Line from '../components/Line.vue';
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
            showModal: false
        };
    },
    methods: {
        async getForumPost() {
            const token = localStorage.getItem('authToken'); 
            try {
                const response = await axios.get('/getAllForumPosts', {
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
                const response = await axios.get('/getUserProfile', {
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
                    const response = await axios.post('/createForumPost', {
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
        }
    },
    mounted() {
        this.getForumPost();
    }
};
</script>

<style>
#forum-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100vh;
    overflow-y: auto; /* Allow scrolling if content overflows */
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
    padding: 20px;
    box-sizing: border-box;
    z-index: 1000;
}

.create-post-button {
    margin-left: auto;
    padding: 10px 20px;
    font-size: 16px;
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
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
}

.modal {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-height: 80vh;
    overflow-y: auto;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
</style>
