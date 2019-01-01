import axios from 'axios';

const state = {
  tasks: null,
  task: null,
  responsible: [],
  error: false,
  errors: {},
};

const getters = {
  
};

const mutations = {
  SET_TASKS: (state, payload) => {
    state.tasks = payload;
  },

  SET_PROJECT: (state, payload) => {
    state.project = payload;
  },

  SET_RESPONSIBLE: (state, payload) => {
    let res = payload.executors;
    res.forEach(function(item, i, res){
      for( let key in item ) {
        if (key === 'id') {
          state.responsible.push(item[key]);
        }
      }
    })
  },

  ADD_PROJECT: (state, payload) => {
    state.projects.push(payload);
  },

  REMOVE_PROJECT: (state, payload) => {
    state.projects.push(payload);
  },

  SET_ERROR: (state, payload) => {
    state.error = payload;
  },

  SET_ERRORS: (state, payload) => {
    state.errors = payload;
  }
};

const actions = {
  GET_TASKS({commit}) {
    axios.get('tasks').then(response => {
      commit('SET_TASKS', response.data.data);
    }).catch(error => {
      commit('SET_ERROR', true);
      commit('SET_ERRORS', error.data);
    });
  },

  GET_PROJECT({commit}, value)  {
    axios.get('projects/' + value).then(response => {
      commit('SET_PROJECT', response.data.data);
      commit('SET_RESPONSIBLE', response.data.data)
    }).catch(error => {
      commit('SET_ERROR', true);
      commit('SET_ERRORS', error.data);
      // if (error.response.status === 403) {
      //  this.$router.push({name: '404'});
      // }
    });
  },

  SAVE_PROJECT: async (context, payload) => {
    let {data} = await axios.post('http://yourwebsite.com/api/todo');
    context.commit('ADD_PROJECT', payload);
  },

  EDIT_PROJECT: async (context, payload) => {
    let {data} = await axios.post('http://yourwebsite.com/api/todo');
    context.commit('SET_PROJECT', payload);
  },

  DELETE_PROJECT: async (context, payload) => {
    let {data} = await axios.post('http://yourwebsite.com/api/todo');
    context.commit('REMOVE_PROJECT', payload);
  },
};

export default {
  state,
  getters,
  mutations,
  actions,
};