// resources/assets/js/views/ProjectItem.vue

<template>
	<div>
		<section>
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="title">
							<h2 >{{project.title}}</h2>
						</div>
					</div>
				</div>
				<div class="row divider">
					<div class="col-md-9 col-lg-10">
						<div class="status">
							<a href="#info" v-on:click="currentTab = 'project-info'" v-bind:class="['status_item', { active: currentTab === 'project-info' }]">Общие сведения</a>
							<a href="#tasks" v-on:click="currentTab = 'project-tasks'" v-bind:class="['status_item', { active: currentTab === 'project-tasks' }]">Задачи</a>
							<span class="status_item">Документы <span class="status_count" >0</span></span>
						</div>
					</div>
				</div>
			</div>
		</section>
		<keep-alive>
		    <component
		      v-bind:is="currentTabComponent"
		      class="tab" 
		      v-bind:project_item="project" 
		      v-bind:project_responsible="responsible" 
		      v-bind:project_users="users"
		    ></component>
		</keep-alive>
	</div>
</template>
<script>
import ProjectInfo from './ProjectInfo'
import ProjectTasks from './ProjectTasks'

  export default {
  	data() {
  		return {
  			project: [],
  			responsible: [],
  			users: [],
  			currentTab: 'project-tasks',
  			isHidden: false,
  			edit_success: false,
            error: false,
            errors: {}
  		}
  	},
  	components: {
	    'project-info': ProjectInfo,
	    'project-tasks': ProjectTasks
	},
	computed: {
	    currentTabComponent () {
	      return this.currentTab
	    }
	},

  	mounted() {
	  	this.getProject();
	  	this.getUsers();
	},
  	methods: {
	  	getProject() {
  			var app = this;
	  		this.axios.get('projects/' + this.$route.params.id).then(response => {
	  			app.project = response.data.data;
	  			let res = response.data.data.executors;
	            res.forEach(function(item, i, res){
	              for( let key in item ) {
	                if (key === 'id') {
	                  app.responsible.push(item[key]);
	                }
	              }
	            })
            }).catch(error => {
                app.error = true;
                app.errors = error.data;
    //             if (error.response.status === 403) {
				// 	this.$router.push({name: '404'});
				// }
            });  
	  	},
	  	getUsers() {
  			var app = this;
	  		this.axios.get('users').then(response => {
	  			app.users = response.data.data;
            }).catch(error => {
                app.error = true;
                app.errors = error.data;
            });  
	  	}
  	},
  	filters: {
  		status(value) {
  			if (!value) return 'Закрыто';
  			return 'Открыто';
  		}
  	}
  }
</script>
