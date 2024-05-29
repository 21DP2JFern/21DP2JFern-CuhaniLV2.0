import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '../views/LoginView.vue';
import RegisterView from '../views/RegisterView.vue';
import HomeView from '../views/HomeView.vue';
import ProfileView from '../views/ProfileView.vue';
import SocialView from '../views/SocialView.vue';
import ForgotPassView from '../views/ForgotPassView.vue';
import NewsView from '../views/NewsView.vue';
import ForumsHubView from '../views/ForumsHubView.vue';
import NewForumsPostView from '../views/NewForumPost.vue';
import PostDetails from '../components/PostDetails.vue'; // Corrected import path for a component
import EditProfileView from '../views/EditProfileView.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/login'
    },
    {
      path: '/EditProfileView',
      name: 'EditProfileView',
      component: EditProfileView,
    },
    {
      path: '/ForumPost/:postId',
      name: 'PostDetails',
      component: PostDetails,
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView
    },
    {
      path: '/ForgotPass',
      name: 'ForgotPass',
      component: ForgotPassView
    },
    {
      path: '/home',
      name: 'home',
      component: HomeView
    },
    {
      path: '/profile',
      name: 'profile',
      component: ProfileView
    },
    {
      path: '/friends',
      name: 'friends',
      component: SocialView
    },
    {
      path: '/news',
      name: 'news',
      component: NewsView
    },
    {
      path: '/forums_hub',
      name: 'forumshub',
      component: ForumsHubView
    },
    {
      path: '/NewForumPost',
      name: 'forumspost',
      component: NewForumsPostView
    }
  ],
});

router.beforeEach((to, from, next) => {
  if (localStorage.getItem('authToken') && (to.path === '/login' || to.path === '/register')) {
    next({ path: '/home' });
  } else {
    next();
  }
});

export default router;