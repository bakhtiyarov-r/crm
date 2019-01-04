// resources/assets/js/views/Tasks.vue
<template>
	<div>
		<section>
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="title">
							<h2 >{{task.title}}</h2>
						</div>
					</div>
				</div>
				<div class="row divider">
					<div class="col-md-9 col-lg-10">
						<div class="status">
							<a href="#info" v-on:click="currentTab = 'task-info'" v-bind:class="['status_item', { active: currentTab === 'task-info' }]">Общие сведения</a>
							<!-- <a href="#subtasks" v-on:click="currentTab = 'sub-tasks'" v-bind:class="['status_item', { active: currentTab === 'sub-tasks' }]">Подзадачи</a> -->
							<a href="#info" v-on:click="currentTab = 'task-documents'" v-bind:class="['status_item', { active: currentTab === 'task-documents' }]">Документы
								<span class="status_count" >{{getCountItems('documents')}}</span>
							</a>
							<span class="status_item">Комментарии <span class="status_count" >0</span></span>
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
  import TaskInfo from './TaskInfo'
  import TaskDocuments from './TaskDocuments'
  import { mapState } from 'vuex';
  export default {
  	data() {
  		return {
  			isHidden: false,
  			currentTab: 'task-info',
  		}
  	},
  	components: {
	    'task-info': TaskInfo,
	    'task-documents': TaskDocuments
	},
  	mounted() {
	  	this.$store.dispatch('getTask', this.$route.params.id);
	  	this.$store.dispatch('getUsers');
	},
	computed: {
	    currentTabComponent () {
	      return this.currentTab
	    },
	    ...mapState({
    		task: state => state.tasks.item,
    		users: state => state.users.items,
    	}),
	},
  	methods: {
	  	getCountItems(count) {
	  		if (this.task[count]) {
	  			return this.task[count].length;
	  		}
		}
  	}
  }
</script>
