import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import VueAxios from 'vue-axios';
import projects from './modules/projects';
import tasks from './modules/tasks';
import users from './modules/users';
import departments from './modules/departments';

Vue.use(Vuex);
Vue.use(VueAxios, axios);
axios.defaults.baseURL = 'http://mysite.local/api';

export const store = new Vuex.Store({
  
  modules: {
    projects,
    tasks,
    users,
    departments,
  },
});