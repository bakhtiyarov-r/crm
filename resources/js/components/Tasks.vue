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
							<span class="status_item" @click="status_filter = 'opened'" v-bind:class="{ active: status_filter == 'opened'}">Открытые 
								<span class="status_count" >
									{{getCountItems('opened')}}
								</span>
							</span>
							<span class="status_item" @click="status_filter = 'immediate'" v-bind:class="{ active: status_filter == 'immediate'}">Срочные 
								<span class="status_count">
									{{this.getCountItems('immediate')}}
								</span>
							</span>
							<span class="status_item" @click="status_filter = 'closed'" v-bind:class="{ active: status_filter == 'closed'}">Выполнены 
								<span class="status_count">
									{{this.getCountItems('closed')}}
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
						<div class="filter_name">
							<label for="" >
								Ответственный:
							</label>
							<select v-model="task_author"> 
								<option value="all">Все</option>
								<option v-for="author in userList" v-bind:value="author.id">{{ author.name }}&nbsp;{{ author.profile.surname }}</option>
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
				<div class="row tasks_item" v-for="task in taskList" v-bind:class="{ done: !task.opened, red: task.immediate}" :key="task.id">
					<div class="col-lg-2">
						{{task.opened | status}}
					</div>
					<div class="col-lg-4">
						<a :href="'/tasks/' + task.id">{{task.title}}</a>
					</div>
					<div class="col-lg-3">
						{{task.deadline | date}}
					</div>
					<div class="col-lg-3">
						{{task.created_at | date}}
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
            task_author: this.$auth.user().id,
            task_name_filter: '',
            status_filter: 'opened'
  		}
  	},
  	mounted() {
	  	this.getTasks();
	},
  	methods: {
	  	getTasks() {
  			var app = this;
	  		this.axios.get('tasks').then(response => {
	  			app.tasks = response.data.data;
	  			//console.log(app.tasks)
            }).catch(error => {
                app.error = true;
                app.errors = error.data;
                
            });  
	  	},
        getCountItems(count) {
        	return this.tasks.filter(elem => {
        		if (!count) {
        			return true;
        		} else if (count == 'closed') {
        			return !elem.opened;
        		}
        		else {
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
            			let arr = elem.executors;
            			return arr.some(item => {
            				return item.id == app.task_author
            			})
            		}
            	})
            	.filter(elem => {
            		if(!app.status_filter) {
            			return true;
            		} else if(app.status_filter == 'closed') {
            				return !elem.opened;
            		}
            		else {
            			return elem[app.status_filter];
            		}
            	})
        },
        userList() {
        	var app = this;
        	var pr = app.tasks;
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
    },
  	filters: {
  		status(value) {
  			if (!value) return 'Закрыто';
  			return 'Открыто';
  		},
  		date(value) {
  			if (!value) return;
  			return value.split('-').reverse().join('.');
  		}
  	}
  }
</script>
