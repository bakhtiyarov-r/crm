// resources/assets/js/views/Tasks.vue
<template>
	<div>
		<section>
			<div class="container">
				<div class="row">
					<div class="col-lg-1">
						<div class="title">
							<h2 >Задания</h2>
						</div>
					</div>
				</div>
				<div class="row divider">
					<div class="col-md-10 col-lg-11">
						<div class="status">
							<span class="status_item" @click="status_filter = ''" v-bind:class="{ active: status_filter == ''}">Все 
								<span class="status_count" >
									{{getCountItems()}}
								</span>
							</span>
							<span class="status_item" @click="status_filter = 'new'" v-bind:class="{ active: status_filter == 'new'}">Новые 
								<span class="status_count" >
									{{getCountItems('new')}}
								</span>
							</span>
							<span class="status_item" @click="status_filter = 'onthe_go'" v-bind:class="{ active: status_filter == 'onthe_go'}">В работе 
								<span class="status_count">
									{{this.getCountItems('onthe_go')}}
								</span>
							</span>
							<span class="status_item"  @click="status_filter = 'under_consideration'" v-bind:class="{ active: status_filter == 'under_consideration'}">На рассмотрении 
								<span class="status_count">
									{{this.getCountItems('under_consideration')}}
								</span>
							</span>
							<span class="status_item" @click="status_filter = 'done'" v-bind:class="{ active: status_filter == 'done'}">Выполнены 
								<span class="status_count">
									{{this.getCountItems('done')}}
								</span>
							</span>
							<span class="status_item"  @click="status_filter = 'canceled'" v-bind:class="{ active: status_filter == 'canceled'}">Отменены 
								<span class="status_count">
									{{this.getCountItems('canceled')}}
								</span>
							</span>
							<span class="status_item"  @click="status_filter = 'drafts'" v-bind:class="{ active: status_filter == 'drafts'}">Черновики 
								<span class="status_count">
									{{this.getCountItems('drafts')}}
								</span>
							</span>
						</div>
					</div>
					<div class="col-md-2 col-lg-1">
						
					</div>
				</div>	
			</div>		
		</section>
		<section>
			<div class="container filter">
				<div class="row">
					<div class="col-lg-3">
						<div class="filter_search">
							<input type="text" name="search"  placeholder="Поиск по названию" v-model="task_name_filter">
						</div>
					</div>
					<div class="col-lg-9">
						<div class="filter_checkbox">
							<input type="checkbox" id="personal" value="personal" v-model="personal_chkd"/><label for="personal"> Персональное</label>
							<input type="checkbox" id="night" value="night" v-model="night_chkd"/><label for="night"> Ночное</label>
							<input type="checkbox" id="immediate" value="immediate" v-model="immediate_chkd"/><label for="immediate"> Срочное</label>	            
						</div>     	
						<div class="filter_name">
							<select v-model="task_author"> 
								<option value="all">Все</option>
								<option v-for="author in userList" v-bind:value="author.user.name + ' ' + author.user.profile.surname">{{ author.user.name }}&nbsp;{{ author.user.profile.surname }}</option>
							</select>
						</div>
					</div>  	
				</div>  	
			</div>	
		</section>

		<section>
			<div class="container tasks">
				<div class="row tasks_items">
					<div class="col-lg-2">
						Статус
					</div>
					<div class="col-lg-4">
						Название
					</div>
					<div class="col-lg-3">
						Выполнить до
					</div>
					<div class="col-lg-3">
						Дата создания
					</div>
				</div>
				<div class="row tasks_item" v-for="task in taskList" :key="task.id">
					<div class="col-lg-2">
						{{task.opened | status}}
					</div>
					<div class="col-lg-4">
						<a :href="'/tasks/task-id-' + task.id">{{task.title}}</a>
					</div>
					<div class="col-lg-3">
						{{task.deadline}}
					</div>
					<div class="col-lg-3">
						{{task.created_at}}
					</div>
				</div>
			</div>
		</section>
        <div v-show="isHidden" class="overlay">
        </div>
	</div>
</template>
<script>
  export default {
  	data() {
  		return {
  			tasks: [],
  			title: 'Новая задача',
  			description: '',
  			deadline: '',
  			immediate: false,
  			drafts: false,
            error: false,
            errors: {},
            add_task_success: false,
            isHidden: false,
            task_author: this.$auth.user().name + ' ' + this.$auth.user().profile.surname,
            task_name_filter: '',
            personal_chkd: '',
            night_chkd: '',
            immediate_chkd: '',
            status_filter: ''
  		}
  	},
  	mounted() {
	  	this.getTasks();
	},
  	methods: {
	  	getTasks() {
  			var app = this;
	  		this.axios.get('task/show').then(response => {
	  			app.tasks = response.data.data;
            }).catch(error => {
                app.error = true;
                app.errors = error.data;
            });  
	  	},
        getCountItems(count) {
        	return this.tasks.filter(elem => {
        		if (!count) {
        			return true;
        		} else {
        			return elem[count] == true; 
        		} 
        	}).length
		}
  	},
    computed: {
        taskList() {
        	var app = this;
            var taskName = this.task_name_filter.toLowerCase();
            return this.tasks.filter(elem => elem.title.toLowerCase().indexOf(taskName) > -1)
            	.filter(elem => {
            		if(app.task_author == 'all') {
            			return true;
            		} else {
            			return elem.user.name + ' ' + elem.user.profile.surname == app.task_author;
            		}
            	})
            	.filter(elem => {
            		if(!app.personal_chkd) {
            			return true;
            		} else {
            			console.log(app.personal_chkd)
            			//console.log(app.checked_status.includes(elem[app.checked_status]))
            			return elem.personal == app.personal_chkd;
            		}
            	})
            	.filter(elem => {
            		if(!app.night_chkd) {
            			return true;
            		} else {
            			return elem.night == app.night_chkd;
            		}
            	})
            	.filter(elem => {
            		if(!app.immediate_chkd) {
            			return true;
            		} else {
            			return elem.immediate == app.immediate_chkd;
            		}
            	})
            	.filter(elem => {
            		if(!app.status_filter) {
            			return true;
            		} else {
            			return elem[app.status_filter] == true;
            		}
            	})
        },
        userList() {
        	var app = this;
        	var pr = app.tasks;
        	var result = [];

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
  	filters: {
  		status(value) {
  			if (!value) return 'Закрыто';
  			return 'Открыто';
  		}
  	}
  }
</script>
