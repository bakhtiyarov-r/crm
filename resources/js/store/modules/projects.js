import axios from 'axios';

const state = {
  items: [],
  item: {},
  responsible: [],
  error: false,
  errors: {},
  add_success: false,
  edit_success: false,
};

const getters = {
  userList: state => {
    var pr = state.items;
    var result = [];
    if (pr) {
      nextInput:
      for (var i = 0; i < pr.length; i++) {
        var str = pr[i].user.id; // для каждого элемента
        for (var j = 0; j < result.length; j++) { // ищем, был ли он уже?
            if (result[j].user.id == str) continue nextInput; // если да, то следующий
        }
        result.push(pr[i]);
      }

      return result;
    }
  },

  userListTask: state => {
    var pr = state.item.tasks;
    var result = [];
    if (pr) {
      nextInput:
      for (var i = 0; i < pr.length; i++) {
        var str = pr[i].user.id; // для каждого элемента
        for (var j = 0; j < result.length; j++) { // ищем, был ли он уже?
            if (result[j].user.id == str) continue nextInput; // если да, то следующий
        }
        result.push(pr[i]);
      }

      return result;
    }
  },
};

const mutations = {
  setProjects: (state, payload) => {
    state.items = payload;
  },

  setProject: (state, payload) => {
    state.item = payload;
  },

  setResponsible: (state, payload) => {
    let res = payload.executors;
    res.forEach(function(item, i, res){
      for( let key in item ) {
        if (key === 'id') {
          state.responsible.push(item[key]);
        }
      }
    })
  },

  addResponsible: (state, payload) => {
    state.responsible = payload;
  },

  setError: (state, payload) => {
    state.error = payload;
  },

  setErrors: (state, payload) => {
    state.errors = payload;
  },

  setAddSuccess: (state, payload) => {
    state.add_success = payload;
  },

  setEditSuccess: (state, payload) => {
    state.edit_success = payload;
  },
};

const actions = {
  getProjects({commit}) {
    axios.get('projects').then(response => {
      commit('setProjects', response.data.data);
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    });
  },

  getProject({commit}, payload) {
    axios.get('projects/' + payload).then(response => {
      commit('setProject', response.data.data);
      commit('setResponsible', response.data.data)
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    });
  },

  addProject({commit}, payload) {
    axios.post(payload.company + '/projects', payload.data).then(response => {
      commit('setProjects', response.data.data);
      commit('setAddSuccess', true);
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    }); 
    
  },

  addTask({commit}, payload) {
    axios.post(payload.project_id + '/tasks', payload.data).then(response => {
      commit('setProject', response.data.data);
      commit('setAddSuccess', true);
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    }); 
  },

  editProject({commit}, payload) {
    axios.put('projects/' + payload.project_id, payload.data).then(response => {
      commit('setProject', response.data.data);
      commit('setEditSuccess', true);
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    });
  },

  deleteProject({commit}, payload) {
    axios.delete('projects/' + payload.project_id).then(response => {
      window.location = response.data.redirect;
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    });
  },
};

export default {
  state,
  getters,
  mutations,
  actions,
};