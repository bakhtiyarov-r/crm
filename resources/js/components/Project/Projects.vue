// resources/assets/js/views/Project.vue
<template>
	<div>
		<section>
			<div class="container">
				<div class="row">
					<div class="col-lg-1">
						<div class="title">
							<h2 >Проекты</h2>
						</div>
					</div>
				</div>
				<div class="row divider">
					<div class="col-md-9 col-lg-10">
						
					</div>
					<div class="col-md-3 col-lg-2">
						<button-orange btnClass="btn_orange task_add" @click.native="isHidden = true" btnTitle="Создать новый проект"></button-orange>
					</div>
				</div>	
			</div>		
		</section>
		<section>
			<div class="container filter">
				<div class="row">
					<div class="col-lg-3">
						<div class="filter_search">
							<input type="text" name="search"  placeholder="Поиск по названию" v-model="project_name_filter">
						</div>
					</div>
					<div class="col-lg-9">
						<div class="filter_checkbox">
							          
						</div>     	
						<div class="filter_name" >
							<select v-model="project_author"> 
								<option value="all">Все</option>
								<option v-for="author in userList" v-bind:value="author.user.name + ' ' + author.user.profile.surname">{{ author.user.name }}&nbsp;{{ author.user.profile.surname }}</option>
							</select>
							<!-- <a href="#" v-for="author in tasks.tasks">{{ author.user.name }}&nbsp;{{ author.user.profile.surname }}&nbsp;&nbsp;</a> -->
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
					<div class="col-lg-3">
						Название
					</div>
					<div class="col-lg-7">
						Описание
					</div>
				</div>
				<div class="row tasks_item" v-for="project in projectList" :key="project.id">
					<div class="col-lg-2">
						{{project.opened | status}}
					</div>
					<div class="col-lg-3">
						<a :href="'/projects/' + project.id">{{project.title}}</a>
					</div>
					<div class="col-lg-7">
						{{project.description}}
					</div>
				</div>
			</div>
		</section>
		<div v-show="isHidden" class="taskadd__form">
			<div class="taskadd__form_close" @click="isHidden = false">X</div>
			<div class="title">
				<h2 >Добавить проект</h2>
			</div>
			<div class="alert alert-danger" v-if="error && !add_success">
                <p>При добавлении произошла ошибка</p>
            </div>
            <div class="alert alert-success" v-if="add_success">
                <p>Проект добавлен</p>
            </div>
            <form autocomplete="off" @submit.prevent="addProject" method="post">
                <div class="form-group form-group_half-width">
                    <label for="title">Название</label>
                    <input type="text" id="title" class="form-control" v-model="title" required>
                </div>
                
                <div class="form-group" style="width: 100%;">
                    <label for="description">Описание</label>
                    <textarea type="text" id="description" class="form-control" v-model="description">
                    </textarea> 
                </div>
                <div class="form-group" style="width: 100%;">
                	<div v-for="user in users" :key="user.id" style="display:flex;width:200px;justify-content: space-between;align-items: center;">
	                	<label :for="user.id">{{user.name}} {{user.profile.surname}}</label>
	                	<input type="checkbox" :id="user.id" class="form-control" :value="user.id" v-model="responsible" >
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
	  			title: 'Новый проект',
	  			description: '',
	            isHidden: false,
	            project_name_filter: '',
	            project_author: 'all',
	  		}
	  	},
	  	mounted() {
		  	this.$store.dispatch('getProjects');
		  	this.$store.dispatch('getUsers');
		},
	  	methods: {
	  		addProject() {
	  			this.$store.dispatch('addProject', {
	  					data: {
			  				title: this.title,
				  			description: this.description,
				  			responsible: this.responsible,
				  		},
			  			company: this.$auth.user().company_id,
			  		},
			  			
		  		);
	  		}
	  	},
	    computed: {
	    	...mapState({
	    		projects: state => state.projects.items,
	    		error: state => state.projects.error,
	    		errors: state => state.projects.errors,
	    		add_success: state => state.projects.add_success,
	    		users: state => state.users.items,
	    	}),
	    	...mapGetters([
	    		'userList'
	    	]),
	        projectList() {
	        	var app = this;
	            var projectName = this.project_name_filter.toLowerCase();
	            if (this.projects) {
	            	return this.projects.filter(elem => elem.title.toLowerCase().indexOf(projectName) > -1)
	            	.filter(elem => {
	            		if(app.project_author == 'all') {
	            			return true;
	            		} else {
	            			return elem.user.name + ' ' + elem.user.profile.surname == app.project_author;
	            		}
	            	})
	            }
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
