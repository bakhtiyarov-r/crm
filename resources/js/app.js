import Vue from 'vue'
import VueRouter from 'vue-router'
import axios from 'axios';
import VueAxios from 'vue-axios';

Vue.use(VueRouter)
Vue.use(VueAxios, axios);
axios.defaults.baseURL = 'http://mysite.local/api';

import App from './components/App'
import UserItem from './components/UserItem'
import Dashboard from './components/Dashboard'
import Company from './components/Company'
import Projects from './components/Projects'
import ProjectItem from './components/ProjectItem'
import Tasks from './components/Tasks'
import TaskItem from './components/TaskItem'
import Home from './components/Home'
import Admin from './components/Admin'
import Register from './components/Register'
import Login from './components/Login'
import NotFound from './components/NotFound'
import ButtonOrange from './components/ButtonOrange'

Vue.component('button-orange', ButtonOrange)

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/company',
      name: 'company',
      component: Company,
      meta: {
        auth: true
      }
    },
    {
      path: '/users/:id',
      name: 'user-item',
      component: UserItem,
      meta: {
        auth: true
      }
    },
    {
      path: '/projects',
      name: 'projects',
      component: Projects,
      meta: {
        auth: true
      }
    },
    {
      path: '/projects/:id',
      name: 'project-id',
      component: ProjectItem,
      meta: {
        auth: true
      }
    },
    {
      path: '/tasks',
      name: 'tasks',
      component: Tasks,
      meta: {
        auth: true
      }
    },
    {
      path: '/tasks/:id',
      name: 'task-id',
      component: TaskItem,
      meta: {
        auth: true
      }
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: Dashboard,
      meta: {
        auth: true
      }
    },
    {
      path: '/admin',
      name: 'admin',
      component: Admin,
    },
    {
      path: '/register',
      name: 'register',
      component: Register,
      meta: {
        auth: false
      }
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
      meta: {
        auth: false
      }
    },
    { path: '/404',
      name: '404',
      component: NotFound
    },  
    // { path: '*',
    //   redirect: '/404'
    // }, 
  ],
});

Vue.router = router
Vue.use(require('@websanova/vue-auth'), {
   auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
   http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
   router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});
App.router = Vue.router
new Vue(App).$mount('#app');

// const app = new Vue({
//   el: '#app',
//   components: { App },
//   router,
// });