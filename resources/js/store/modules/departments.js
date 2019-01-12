import axios from 'axios';

const state = {
  items: [],
  item: {},
  error: false,
  errors: {},
  success: false,
  add_success: false,
};

const getters = {
  
};

const mutations = {
  setDepartments: (state, payload) => {
    state.items = payload;
  },

  // setDepartment: (state, payload) => {
  //   state.item = payload;
  // },

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
  getDepartments({commit}) {
    axios.get('departments').then(response => {
      commit('setDepartments', response.data.data);
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    });
  },

  // getDepartment({commit}, payload) {
  //   axios.get('departments/' + payload).then(response => {
  //     commit('setDepartment', response.data.data);
  //   }).catch(error => {
  //     commit('setError', true);
  //     commit('setErrors', error.data);
  //   });
  // },

  addDepartment({commit}, payload) {
    axios.post('departments', payload.data).then(response => {
      commit('setDepartments', response.data.data);
      commit('setAddSuccess', true);
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    });
  },

  // editDepartment({commit}, payload) {
  //   axios.put('departments/' + payload.user_id, payload.data
  //   ).then(response => {
  //     commit('setDepartments', response.data.data);
  //     commit('setSuccess', true);
  //   }).catch(error => {
  //       commit('setError', true);
  //       commit('setErrors', error.data);
  //   }); 
  // },

  // deleteDepartment({commit}, payload) {
  //   axios.delete('departments/' + payload.user_id).then(response => {
  //       window.location = response.data.redirect;
  //   }).catch(error => {
  //       commit('setError', true);
  //       commit('setErrors', error.data);
  //   });  
  // },
  
};

export default {
  state,
  getters,
  mutations,
  actions,
};