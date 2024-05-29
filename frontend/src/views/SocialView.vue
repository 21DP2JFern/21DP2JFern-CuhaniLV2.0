<template>
    <Header  />

    <div id="friends-div">
        <h1 id="friends-header">Your friends</h1>
        <Line id="line" />
        <div id="friend-prof-container">
            <div class="friend-card">
                <div class="prof-pic"></div>
                <p class="FUsername">Dave420</p>
                <button class="ViewFProf">View profile</button>
            </div>
            <div class="friend-card">
                <div class="prof-pic"></div>
                <p class="FUsername">Dave420</p>
                <button class="ViewFProf">View profile</button>
            </div>
            <div class="friend-card">
                <div class="prof-pic"></div>
                <p class="FUsername">Dave420</p>
                <button class="ViewFProf">View profile</button>
            </div>
            <div class="friend-card">
                <div class="prof-pic"></div>
                <p class="FUsername">Dave420</p>
                <button class="ViewFProf">View profile</button>
            </div>
            <div class="friend-card">
                <div class="prof-pic"></div>
                <p class="FUsername">Dave420</p>
                <button class="ViewFProf">View profile</button>
            </div>
            <div class="friend-card">
                <div class="prof-pic"></div>
                <p class="FUsername">Dave420</p>
                <button class="ViewFProf">View profile</button>
            </div>
        </div>
    </div>

    <div id="find-friends-div">
        <h1 id="friends-header">Find friends</h1>
        <Line id="line" />
    </div>

    <span id="bottom"></span>
</template>

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
        margin-top: 10px;
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

    .ViewFProf{
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

    #bottom{
        bottom: 0;
        height: 210px;
    }

</style>

<script>
    import Header from '../components/Header.vue'
    import Line from '../components/Line.vue'
    export default{
        name: "Home",
        components:{
            Header,
            Line
        }
    }
</script>