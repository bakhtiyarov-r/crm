import axios from 'axios';

const state = {
  items: [],
  item: {profile: {}},
  avatar: '',
  error: false,
  errors: {},
  success: false,
  add_success: false,
};

const getters = {
  
};

const mutations = {
  setUsers: (state, payload) => {
    state.items = payload;
  },

  setUser: (state, payload) => {
    state.item = payload;
  },

  setAvatar: (state, payload) => {
    state.avatar = payload;
  },

  setAddSuccess: (state, payload) => {
    state.add_success = payload;
  },

  setSuccess: (state, payload) => {
    state.success = payload;
  },

  setError: (state, payload) => {
    state.error = payload;
  },

  setErrors: (state, payload) => {
    state.errors = payload;
  },
};

const actions = {
  getUsers({commit}) {
    axios.get('users').then(response => {
      commit('setUsers', response.data.data);
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    });
  },
  
  getUser({commit}, payload) {
    axios.get('users/' + payload).then(response => {
      commit('setUser', response.data.data);
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    });
  },

  addUser({commit}, payload) {
    axios.post('users', payload.data).then(response => {
      commit('setUsers', response.data.data);
      commit('setAddSuccess', true);
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    });
  },

  editUser({commit}, payload) {
    axios.put('users/' + payload.user_id, payload.data
    ).then(response => {
      commit('setUser', response.data.data);
      commit('setSuccess', true);
    }).catch(error => {
        commit('setError', true);
        commit('setErrors', error.data);
    }); 
  },

  editProfile({commit}, payload) {
    axios.put('profile/edit', payload.data
    ).then(response => {
      commit('setSuccess', true);
    }).catch(error => {
        commit('setError', true);
        commit('setErrors', error.data);
    }); 
  },

  deleteUser({commit}, payload) {
    axios.delete('users/' + payload.user_id).then(response => {
        window.location = response.data.redirect;
    }).catch(error => {
        commit('setError', true);
        commit('setErrors', error.data);
    });  
  },

  updateAvatar({commit}, payload) {
    axios.post('profile/avatar', payload, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(response => {
        commit('setAvatar', response.data.data);
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