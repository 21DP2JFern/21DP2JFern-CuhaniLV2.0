<template>
  <Header />
  <div v-if="post" id="post-container">
    <router-link to="/forums_hub">
      <button id="return-but">Return to Forums</button>
    </router-link>
    <div id="post-content-container">
      <h1 id="post-nosaukums">{{ post.nosaukums }}</h1>
      <p>{{ post.saturs }}</p>
      <p>Author: {{ post.authorProfile.username }}</p>
      <p>Date: {{ post.date }}</p>
    </div>
    
    

    <!-- Edit and Delete Post Buttons -->
    <button id="edit-but" v-if="isAuthorOrAdmin" @click="editPost">Edit Post</button>
    <button id="delete-but" v-if="isAuthorOrAdmin" @click="deletePost">Delete Post</button>
    <h2>Comments:</h2>
    <button id="add-comment-but" @click="showModal = true">Add Comment</button>
    <!-- Display comments -->
    <div v-if="comments.length" id="comments-container">
      <div v-for="(comment, index) in comments" :key="index" class="comment-wrapper">
        <div>
          <p><strong>Author:</strong> {{ comment.author_id }}</p>
          <p><strong>Content:</strong> {{ comment.content }}</p>
          <p><strong>Date:</strong> {{ comment.created_at }}</p>
        </div>
      </div>
    </div>
    <div v-else>
      <p>No comments yet.</p>
    </div>
  </div>

  <div v-if="showModal" class="modal">
    <div class="modal-content">
      <span class="close" @click="showModal = false">&times;</span>
      <form @submit.prevent="addComment">
        <label for="commentContent">Comment:</label>
        <textarea id="commentContent" v-model="commentContent" required></textarea>
        <button  type="submit">Submit Comment</button>
      </form>
    </div>
  </div>

  <div v-if="showEditModal" class="modal">
  <div class="modal-content">
    <span class="close" @click="showEditModal = false">&times;</span>
    <form @submit.prevent="saveEditedPost">
      <label for="editedTitle">Title:</label>
      <input id="post-title-input" type="text" v-model="editedTitle">

      <label for="editedContent">Content:</label>
      <textarea id="post-content-input" v-model="editedContent"></textarea>

      <button id="post-but" type="submit">Save Changes</button>
    </form>
  </div>
</div>
</template>

<script>
import axios from 'axios';
import Header from'../components/Header.vue';
export default {
  components:{
    Header,
  },
  data() {
    return {
      post: null,
      showModal: false,
      commentContent: '',
      comments: [],
      showEditModal: false,
      editedTitle: '',
      editedContent: '',
      currentUser: null, // To hold the current user information
    };
  },
  computed: {
  isAuthorOrAdmin() {
    return this.currentUser && (this.currentUser.id === this.post.authorProfile.id || this.currentUser.role_id === 1);
  },
  },
  methods: {
    async getPost(id) {
      const token = localStorage.getItem('authToken');
      try {
        const response = await axios.get(`/getForumPost?id=${id}`, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        const postData = response.data.data;
        const authorId = postData.autors;
        const authorProfile = await this.fetchProfile(authorId);
        postData.authorProfile = authorProfile;
        this.post = postData;

        // Fetch comments after getting the post
        await this.fetchComments(id);

        // Fetch the current user
        this.currentUser = await this.fetchCurrentUser();
      } catch (error) {
        console.error("There was an error fetching the post:", error);
      }
    },
    async addComment() {
      const token = localStorage.getItem('authToken');
      const postId = this.$route.params.postId;
      const authorId = this.post.authorProfile.id;
      try {
        const response = await axios.post(`/createComment`, {
          post_id: postId,
          author_id: authorId,
          content: this.commentContent
        }, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        if (response.data && response.data.status) {
          alert('Comment added successfully');
          this.commentContent = '';
          this.showModal = false;
          await this.fetchComments(postId);
        } else {
          alert('Error adding comment');
        }
      } catch (error) {
        console.error('Error adding comment:', error);
        alert('Error adding comment');
      }
    },
    async fetchProfile(authorId) {
      const token = localStorage.getItem('authToken');
      try {
        const response = await axios.get(`/getUserProfile?id=${authorId}`, {
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
    async fetchComments(postId) {
      const token = localStorage.getItem('authToken');
      try {
        const response = await axios.get('/getComments', {
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json'
          },
          params: { post_id: postId }
        });
        if (response.data && response.data.comments) {
          this.comments = response.data.comments;
        } else {
          console.error('Comments data is missing or malformed', response.data);
          this.comments = [];
        }
      } catch (error) {
        console.error('Error fetching comments:', error);
        this.comments = [];
      }
    },
    async fetchCurrentUser() {
      const token = localStorage.getItem('authToken');
      try {
        const response = await axios.get('/profile', {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        return response.data.data;
      } catch (error) {
        console.error('Error fetching current user:', error);
        return null;
      }
    },
    async deletePost() {
      if (!confirm('Are you sure you want to delete this post?')) return;
      
      const token = localStorage.getItem('authToken');
      const postId = this.post.id;
      try {
        const response = await axios.delete(`/deletePost/${postId}`, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        if (response.data && response.data.status) {
          alert('Post deleted successfully');
          this.$router.push('/forums_hub');
        } else {
          alert('Error deleting post');
        }
      } catch (error) {
        console.error('Error deleting post:', error);
        alert('Error deleting post');
      }
    },
    editPost() {
      this.editedTitle = this.post.nosaukums;
      this.editedContent = this.post.saturs;
      this.showEditModal = true;
    },
    async saveEditedPost() {
      const token = localStorage.getItem('authToken');
      const postId = this.post.id;
      try {
        const response = await axios.post(`/editForumPost/${postId}`, {
          nosaukums: this.editedTitle,
          saturs: this.editedContent
        }, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        if (response.data && response.data.status) {
          alert('Post edited successfully');
          this.showEditModal = false;
          await this.getPost(postId); // Refresh the post data
        } else {
          alert('Error editing post');
        }
      } catch (error) {
        console.error('Error editing post:', error);
        alert('Error editing post');
      }
    },
  },
  mounted() {
    const postId = this.$route.params.postId;
    this.getPost(postId);
  }
};
</script>

<style scoped>
#post-container{
  width:60vw;
  height: 70vh;
  border-radius: 25px;
  background-color: var(--color-element);
  overflow-y: scroll;
}

#return-but{
  position:relative;
  margin-top: 2.5vh;
  margin-left:1vw;
  width: 10vw;
  height: 5vh;
  left: 48vw;
  font-size: 16px;
  border:none;
  background-color: var(--color-red);
  color:white;
  border-radius: 25px;

}
#return-but:hover{
    transform: scale(1.10);
    border: 1px white solid;
}


p{
  font: 'Inter';
}

h2{
  margin-left:2.5vw;
  text-align: center;
}
#post-content-container{
  left: 10vw;
  width: 30vw;
  height:auto;
  padding-left:2.5vw;
  margin-top: -5vh;

}

#comments-container{
  background-color: var(--color-red);
  padding-left: 2.5vw;
  width:57.5vw;
  margin-left:1.25vw;
  border-radius: 20px;
  margin-top: 1vh;
}
.comment-wrapper {
  margin-bottom: 20px; 
  
}

.modal-content{
  height:50vh;
}

#edit-but{
  position:relative;
  margin-top: 2.5vh;
  margin-left:1vw;
  width: 6vw;
  height: 4vh;
  font-size: 16px;
  border:none;
  background-color: var(--color-red);
  color:white;
  border-radius: 25px;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

#edit-but:hover{
  transform: scale(1.10);
    border: 1px white solid;
}

#delete-but{
  position:relative;
  margin-top: 2.5vh;
  margin-left:0.3vw;
  width: 6vw;
  height: 4vh;
  font-size: 16px;
  border:none;
  background-color: #990d03;;
  color:white;
  border-radius: 25px;
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}

#delete-but:hover{
  transform: scale(1.10);
    border: 1px white solid;
}

#add-comment-but{
  position:relative;
  margin-left:1vw;
  margin-bottom: 1vh;
  width: 10vw;
  height: 5vh;
  font-size: 16px;
  border:none;
  background-color: var(--color-red);
  color:white;
  border-radius: 25px;

}
#add-comment-but:hover{
    transform: scale(1.10);
    border: 1px white solid;
}



.return-link:hover {
  background-color: #0056b3;
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
