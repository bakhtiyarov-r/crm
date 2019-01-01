//ProjectItemInfo.vue
<template>
	<div>
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
								            
						</div>     	
						<div class="filter_name" >
							<select v-model="project_author"> 
								<option value="all">Все</option>
								<option v-for="author in userListTask" v-bind:value="author.user.id">{{ author.user.name }}&nbsp;{{ author.user.profile.surname }}</option>
							</select>
							<!-- <a href="#" v-for="author in tasks.tasks">{{ author.user.name }}&nbsp;{{ author.user.profile.surname }}&nbsp;&nbsp;</a> -->
						</div>
					</div>  	
				</div>  	
			</div>	
			<div class="action__btns">
				<button-orange btnClass="btn_orange task_add" @click.native="isHidden = true" btnTitle="Создать новую задачу"></button-orange>
			</div>
		</section>
		<section>
			<div class="container tasks">
				<div class="row tasks_items">
					<div class="col-lg-2">
						Статус
					</div>
					<div class="col-lg-5">
						Название
					</div>
					<div class="col-lg-3">
						
					</div>
					<div class="col-lg-2">
						Выполнить до
					</div>
				</div>
				<div class="row tasks_item" v-for="task in taskList" v-bind:class="{ done: !task.opened, red: Date.parse(task.deadline) < Date.parse(new Date()) }" :key="task.id">
					<div class="col-lg-2" v-bind:class="{important: task.immediate}">
						{{task.opened | status}}
					</div>
					<div class="col-lg-5">
						<a :href="'/tasks/' + task.id">{{task.title}}</a>
					</div>
					<div class="col-lg-3">
						
					</div>
					<div class="col-lg-2">
						{{task.deadline | date}}
					</div>
				</div>
			</div>
		</section>
		<div v-show="isHidden" class="taskadd__form">
			<div class="taskadd__form_close" @click="isHidden = false">X</div>
			<div class="title">
				<h2 >Добавить задачу</h2>
			</div>
			<div class="alert alert-danger" v-if="error && !add_success">
                <p>При добавлении произошла ошибка</p>
            </div>
            <div class="alert alert-success" v-if="add_success">
                <p>Задача добавлена</p>
            </div>
            <form autocomplete="off" @submit.prevent="addTask" method="post">
                <div class="form-group form-group_half-width">
                    <label for="task_name">Название</label>
                    <input type="text" id="task_name" class="form-control" v-model="title" required>
                </div>
                <div class="form-group form-group_half-width" >
                    <label for="deadline">Срок выполнения</label>
                    <input type="date" id="deadline" class="form-control" v-model="deadline">
                </div>
                <div class="form-group" style="width: 100%;">
                    <label for="task_description">Описание</label>
                    <textarea type="text" id="task_description" class="form-control" v-model="description">
                    </textarea> 
                </div>
                <div class="form-group" style="width: 100%;">
                	Ответственные:
                	<div v-for="user in users" :key="user.id" style="display:flex;width:200px;justify-content: space-between;align-items: center;">
	                	<label :for="user.id">{{user.name}} {{user.profile.surname}}</label>
	                	<input type="checkbox" :id="user.id" class="form-control" :value="user.id" v-model="responsible" >
	                </div>
                </div>
                <div class="form-group form-group_half-width">
	                <div class="form-sub-group">
	                    <label for="drafts">Черновик</label>
	                    <input type="checkbox" id="drafts" class="form-control" v-model="drafts" >
	                </div>
                </div>
                <div class="form-group form-group_half-width">
	                <div class="form-sub-group">
	                    <label for="immediate">Срочное</label>
	                    <input type="checkbox" id="immediate" class="form-control" v-model="immediate" >
	                </div>
                </div>
                <button type="submit" class="btn btn-default" >Добавить</button>
            </form>
        </div>
		<div v-show="isHidden" class="overlay">
        </div>
	</div>
</template>
<script>
	import { mapState, mapGetters } from 'vuex';
  export default {
  	data() {
  		return {
  			responsible: [],
  			title: 'Новая задача',
  			description: '',
  			deadline: '',
  			immediate: false,
  			drafts: false,
            isHidden: false,
            project_author: 'all',
            task_name_filter: '',
  		}
  	},
  	methods: {
  		addTask() {
  			this.$store.dispatch('addTask', {
	            data: {
	            	title: this.title,
		  			description: this.description,
		  			deadline: this.deadline,
		  			immediate: this.immediate,
		  			drafts: this.drafts,
		  			responsible: this.responsible
	            },
		            project_id: this.$route.params.id,
		        },
	        ); 
	  	},
  	},
    computed: {
    	...mapState({
        	project: state => state.projects.item,
			error: state => state.projects.error,
			errors: state => state.projects.errors,
			add_success: state => state.projects.add_success,
        	users: state => state.users.items,
        }),
        ...mapGetters([
    		'userListTask'
    	]),
    	sort() {
			var app = this;
			if (app.project.tasks) {
				return this.project.tasks
					.sort((a, b) => a.deadline > b.deadline? 1 : -1)
					.sort((a, b) => a.opened == b.opened? 0 : a.opened? -1 : 1);
			}
		},
        taskList() {
        	var app = this;
            var taskName = this.task_name_filter.toLowerCase();
            if (app.sort) {
            	return app.sort.filter(elem => elem.title.toLowerCase().indexOf(taskName) > -1)
	        	.filter(elem => {
	        		if(app.project_author == 'all') {
	        			return true;
	        		} else {
	        			return elem.user.id == app.project_author;
	        		}
	        	})
            }
        },
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
