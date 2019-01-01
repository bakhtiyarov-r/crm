// resources/assets/js/views/ProjectItem.vue

<template>
	<div>
		<section>
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="title">
							<h2>{{project.title}}</h2>
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
		    ></component>
		</keep-alive>
	</div>
</template>
<script>
import ProjectInfo from './ProjectInfo'
import ProjectTasks from './ProjectTasks'
import { mapState } from 'vuex';
  export default {
  	data() {
  		return {
  			currentTab: 'project-tasks',
  		}
  	},
  	components: {
	    'project-info': ProjectInfo,
	    'project-tasks': ProjectTasks
	},
	mounted() {
		this.$store.dispatch('getProject', this.$route.params.id);
  		this.$store.dispatch('getUsers');
	},
	computed: {
		...mapState({
			project: state => state.projects.item,
    		users: state => state.users.items,
    	}),
	    currentTabComponent () {
	      return this.currentTab;
	    },
	    
	},
  	filters: {
  		status(value) {
  			if (!value) return 'Закрыто';
  			return 'Открыто';
  		}
  	}
  }
</script>
