<template>
    <div>
        <Header />

        <!-- Display Friends -->
        <div id="friends-div">
            <h1 id="friends-header">Your friends</h1>
            <Line id="line" />
            <div id="friend-prof-container">
                <div class="friend-card" v-for="friend in friends" :key="friend.id">
                    <div class="prof-pic"></div>
                    <p class="FUsername">{{ friend.Fusername }}</p>
                </div>
            </div>
        </div>

        <!-- Add Friends -->
        <div id="find-friends-div">
            <h1 id="find-friends-header">Add friends</h1>
            <input id="search-bar" type="text" v-model="searchQuery" placeholder="Search for friends..." />
            <button id="search-but" type="submit" @click="searchFriends">Search</button>
            <Line id="line" />
            <div id="search-results">
                <div class="friend-card" v-for="user in searchResults" :key="user.id">
                    <div class="prof-pic"></div>
                    <p class="FUsername">{{ user.username }}</p>
                    <button class="AddFProf" @click="addFriend(user.id)">Add friend</button> <!-- Add friend button -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '../components/Header.vue';
import Line from '../components/line.vue';
import axios from 'axios';

export default {
    name: "Home",
    components: {
        Header,
        Line
    },
    data() {
        return {
            friends: [],        // Store the user's friends
            searchQuery: '',    // Store the search query
            searchResults: []   // Store search results
        };
    },
    methods: {
        // Method to search for users
        searchFriends() {
            const token = localStorage.getItem('authToken');
            axios.post('/api/searchUsers', { query: this.searchQuery }, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                this.searchResults = response.data.data;
            })
            .catch(error => {
                console.error('Error searching users:', error);
            });
        },
        // Method to add a friend
        addFriend(friendId) {
            const token = localStorage.getItem('authToken');
            axios.post('/api/addFriend', { friend_id: friendId }, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                // Update the friends list after adding a friend
                this.getFriends();
            })
            .catch(error => {
                console.error('Error adding friend:', error);
            });
        },
        // Method to retrieve the user's friends list
        getFriends() {
            const token = localStorage.getItem('authToken');
            axios.get('/api/getUserFriends', {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                this.friends = response.data.friends;
            })
            .catch(error => {
                console.error('Error getting user friends:', error);
            });
        },
    },

    mounted() {
        // Fetch the user's friends list when the component is mounted
        this.getFriends();
    }
};
</script>


<style>
    body{
        overflow-y: scroll;
    }

    ::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--color-red);
        border-radius: 12px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    #friends-div{
        background-color: var(--color-element);
        width: 60vw;
        height: 550px;
        border-radius: 25px;
        margin-top:  130px;
    }

    #friends-header{
        font: "Inter";
        color: white;
        font-size: 45px;
        font-weight: 500;
        margin-top: 10px;
        margin-left: 50px;
    }

    #line{
        width: 56.5vw;
        margin-left: 35px;
        margin-top: 1vh;
    }

    #friend-prof-container{
        margin-top: 25px;
        margin-left: 35px;
        width: 90%;
        height: 420px;
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
    }

    .friend-card{
        height: 390px;
        width: 280px;
        top: 0;
        margin-right: 30px;
        border-radius: 25px;
        background-color: var(--color-red);
        display: inline-block;
        
    }

    .prof-pic{
        height: 200px;
        width:200px;
        background-image: url('../assets/some_dude.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        margin-left: auto;
        margin-right: auto;
        margin-top: 35px;
    }

    .FUsername{
        font: "Inter";
        color: white;
        position: relative;
        font-size: 30px;
        font-weight: 500;
        margin-left: 80px;
        margin-top: 20px;
        
    }

    .AddFProf{
        height:40px;
        width: 150px;
        border: none;
        border-radius: 25px;
        display:flex;
        position:relative;
        top: 20px;
        left: 65px;
        display: table-cell;
        vertical-align: middle;
        background-color: var(--color-dark-red);
        color: var(--color-text);
        font: "Inter";
        font-size: 16px;
        font-weight: 550;
    }

    #find-friends-div{
        background-color: var(--color-element);
        width: 60vw;
        height: 550px;
        border-radius: 25px;
        margin-top: 40px;
    }


    #find-friends-header{
        font: "Inter";
        color: white;
        font-size: 45px;
        font-weight: 500;
        margin-top: 10px;
        margin-left: 50px;
        position:relative;
        width:15vw;
        margin-bottom: -2vh;
    }

    #search-bar{
        position: relative;
        left: 16vw;
        top: -2.5vh;
        height: 40px;
        width: 300px;
        border-radius: 25px;
        border: 0;
        font-size: 20px;
        text-indent: 20px;
        font: "Inter";
        color: var(--color-text);
        background-color: #250101;
    }

    #search-but{
        position: relative;
        left: 17vw;
        top: -2.5vh;
        height: 40px;
        width: 150px;
        border-radius: 25px;
        border: 0;
        font-size: 20px;
        font: "Inter";
        color: var(--color-text);
        background-color: #250101;
        text-align: center;
        
    }

    #search-results{
        margin-top: 2vh;
        margin-left: 2vw;
        overflow-x: scroll;
    }
    
    #bottom{
        bottom: 0;
        height: 210px;
    }

</style>
