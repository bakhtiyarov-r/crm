import axios from 'axios';

const state = {
  items: [],
  item: {},
  documents: [],
  executors: [],
  add_success: false,
  edit_success: false,
  add_document_success: false,
  error: false,
  errors: {},
};

const getters = {
  sort: state => {
    return state.items
      .sort((a, b) => a.deadline > b.deadline? 1 : -1)
      .sort((a, b) => a.opened == b.opened? 0 : a.opened? -1 : 1);
  },

  executorsList: state => {
    var pr = state.items;
          var result = [];
      
        for (var i = 0; i < pr.length; i++) {
          var str = pr[i].executors; 

          nextInput:
          for (var k = 0; k < str.length; k++) {
            var exec = str[k].id;
            for (var j = 0; j < result.length; j++) {
                if (result[j].id == exec) continue nextInput; 
            }
            result.push(str[k]);
          }
        }
      return result;
  }
};

const mutations = {
  setTasks: (state, payload) => {
    state.items = payload;
  },

  setTask: (state, payload) => {
    state.item = payload;
  },

  setDocuments: (state, payload) => {
    state.documents = payload;
  },

  setTaskClosed: (state, payload) => {
    state.item.opened = payload;
  },

  setTaskDone: (state, payload) => {
    state.item.done = payload;
  },

  setExecutors: (state, payload) => {
    let res = payload.executors;
    res.forEach(function(item, i, res){
      for( let key in item ) {
        if (key === 'id') {
          state.executors.push(item[key]);
        }
      }
    })
  },

  addExecutors: (state, payload) => {
    state.executors = payload;
  },

  setAddDocumentSuccess: (state, payload) => {
    state.add_document_success = payload;
  },

  setEditSuccess: (state, payload) => {
    state.edit_success = payload;
  },
  
  setError: (state, payload) => {
    state.error = payload;
  },

  setErrors: (state, payload) => {
    state.errors = payload;
  }
};

const actions = {
  getTasks({commit}) {
    axios.get('tasks').then(response => {
      commit('setTasks', response.data.data);
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    });
  },

  getTask({commit}, value)  {
    axios.get('tasks/' + value).then(response => {
      commit('setTask', response.data.data);
      commit('setDocuments', response.data.data.documents)
      commit('setExecutors', response.data.data)
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
      // if (error.response.status === 403) {
      //  this.$router.push({name: '404'});
      // }
    });
  },

  closeTask({commit}, payload) {
    axios.put('tasks/' + payload.task_id + '/close', payload.data).then(response => {
      commit('setTaskClosed', payload.data.opened);
      commit('setEditSuccess', true);
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    });
  },

  doneTask({commit}, payload) {
    axios.put('tasks/' + payload.task_id + '/done', payload.data).then(response => {
      commit('setTaskDone', payload.data.done);
      commit('setEditSuccess', true);
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    });
  },

  editTask({commit}, payload) {
    axios.put('tasks/' + payload.task_id, payload.data).then(response => {
      commit('setTask', response.data.data);
      commit('setEditSuccess', true);
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    });
  },

  deleteTask({commit}, payload) {
    axios.delete('tasks/' + payload.task_id).then(response => {
      window.location = response.data.redirect;
    }).catch(error => {
      commit('setError', true);
      commit('setErrors', error.data);
    });
  },

  addDocument({commit}, payload) {
    axios.post(payload.task_id + '/document', payload.formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(response => {
        commit('setDocuments', response.data.data.documents);
        commit('setAddDocumentSuccess', true);
      }).catch(error => {
        commit('setError', true);
        commit('setErrors', error.data);
      });    
  },

  removeDocument({commit}, payload) {
    axios.put(payload.task_id + '/document', payload.data
    ).then(response => {
        commit('setDocuments', response.data.data.documents);
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