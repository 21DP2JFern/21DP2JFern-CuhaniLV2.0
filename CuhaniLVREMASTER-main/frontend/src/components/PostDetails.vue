<template>
  <div v-if="post">
    <h1>{{ post.nosaukums }}</h1>
    <p>{{ post.saturs }}</p>
    <p>Autors: {{ post.authorProfile.id }}</p>
    <p>Date: {{ post.date }}</p>
    <button @click="showModal = true">Add Comment</button>
    <router-link to="/forums_hub" class="return-link">Return to Forums</router-link>

    <!-- Edit and Delete Post Buttons -->
    <button v-if="isAuthorOrAdmin" @click="editPost">Edit Post</button>
    <button v-if="isAuthorOrAdmin" @click="deletePost">Delete Post</button>

    <!-- Display comments -->
    <div v-if="comments.length">
      <h3>Comments:</h3>
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
        <button type="submit">Submit Comment</button>
      </form>
    </div>
  </div>

  <div v-if="showEditModal" class="modal">
  <div class="modal-content">
    <span class="close" @click="showEditModal = false">&times;</span>
    <form @submit.prevent="saveEditedPost">
      <label for="editedTitle">Title:</label>
      <input type="text" id="editedTitle" v-model="editedTitle">

      <label for="editedContent">Content:</label>
      <textarea id="editedContent" v-model="editedContent"></textarea>

      <button type="submit">Save Changes</button>
    </form>
  </div>
</div>
</template>

<script>
import axios from 'axios';

export default {
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
.comment-wrapper {
  margin-bottom: 20px; /* Adjust the value as needed */
}


.return-link {
  display: inline-block;
  margin-top: 20px;
  padding: 10px 15px;
  color: #fff;
  background-color: #007bff;
  text-decoration: none;
  border-radius: 5px;
  transition: background-color 0.3s;
}

.return-link:hover {
  background-color: #0056b3;
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
  background-color: rgba(0, 0, 0, 0.4);
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
