<template>
	<div>
		<section>
			<div class="container">
				<div class="row content">
					<div class="col-md-8 col-lg-8">
						<div>
							<span class="task_title">Статус:</span>
							<span class="task_value">{{task.opened | status}}</span>
						</div>
						<div>
							<span class="task_title">Создана:</span>
							<span class="task_value">{{task.created_at | date}}</span>
						</div>
						<div>
							<span class="task_title">Крайний срок:</span>
							<span class="task_value">{{task.deadline | date}}</span>
						</div>
						<div>
							<span class="task_title">Срочное:</span>
							<span class="task_value">{{task.immediate| boolToString}}</span>
						</div>
						<div>
							<span class="task_title">В черновике:</span>
							<span class="task_value">{{task.drafts| boolToString}}</span>
						</div>
						<div>
							<span class="task_title">Отменено:</span>
							<span class="task_value">{{task.canceled| boolToString}}</span>
						</div>
						<div>
							<span class="task_title">Автор:</span>
							<span class="task_value" v-if="task.user">{{task.user.name}} {{task.user.profile.surname}}</span>
						</div>
						<div style="display: flex;">
			              <span class="task_title">Ответственные:</span>
			              <div>
			                <span class="task_value" v-for="executor in task.executors" :key="executor.id" v-if="task.executors">{{executor.name}} {{executor.profile.surname}}<br></span>
			              </div>
			            </div>
						<div>
							<span class="task_title">Описание:</span>
							<span class="task_value">{{task.description}}</span>
						</div>
					</div>
					<div class="action__btns">
						<button-orange btnClass="btn_orange task_edit" @click.native="isHidden = true" btnTitle="Править"></button-orange>
						<button-orange btnClass="btn_orange task_delete" @click.native="deleteTask" btnTitle="Удалить"></button-orange>
					</div>
				</div>
			</div>
		</section>
		<div v-show="isHidden" class="taskadd__form">
			<div class="taskadd__form_close" @click="isHidden = false">X</div>
			<div class="title">
				<h2 >Редактировать задачу</h2>
			</div>
			<div class="alert alert-danger" v-if="error && !edit_task_success">
                <p>При редактировании произошла ошибка</p>
            </div>
            <div class="alert alert-success" v-if="edit_task_success">
                <p>Задача отредактирована</p>
            </div>
            <form autocomplete="off" @submit.prevent="editTask" method="post">
                <div class="form-group form-group_half-width">
                    <label for="task_name">Название</label>
                    <input type="text" id="task_name" class="form-control" v-model="task.title" required>
                </div>
                <div class="form-group form-group_half-width">
                    <label for="deadline">Срок выполнения</label>
                    <input type="date" id="deadline" class="form-control" v-model="task.deadline">
                </div>
                <div class="form-group" style="width: 100%;">
                    <label for="task_description">Описание</label>
                    <textarea type="text" id="task_description" class="form-control" v-model="task.description">
                    </textarea> 
                </div>
                <div class="form-group form-group_half-width">
                	<div class="form-sub-group">
	                    <label for="canceled">Открыто</label>
	                    <input type="checkbox" id="done" class="form-control" v-model="task.opened">
	                </div>
	                <div class="form-group" style="width: 100%;">
	                  Ответственые:
	                  <div v-for="user in users" :key="user.id" style="display:flex;width:200px;justify-content: space-between;align-items: center;">
	                    <label :for="user.id">{{user.name}} {{user.profile.surname}}</label>
	                    <input type="checkbox" :id="user.id" class="form-control" :value="user.id" v-model="responsible" checked>
	                  </div>
	                </div>
                </div>
                <div class="form-group form-group_half-width">
	                <div class="form-sub-group">
	                    <label for="immediate">Срочное</label>
	                    <input type="checkbox" id="immediate" class="form-control" v-model="task.immediate" >
	                </div>
	                <div class="form-sub-group">
	                    <label for="canceled">Отменить</label>
	                    <input type="checkbox" id="canceled" class="form-control" v-model="task.canceled">
	                </div>
	                <div class="form-sub-group">
	                    <label for="drafts">В черновик</label>
	                    <input type="checkbox" id="drafts" class="form-control" v-model="task.drafts">
	                </div>
                </div>
                <button type="submit" class="btn btn-default" >Отправить</button>
            </form>
        </div>
        <div v-show="isHidden" class="overlay">
        </div>
	</div>
</template>
<script>
  export default {
  	data() {
  		return {
  			task: [],
  			users: [],
        	responsible: [],
  			isHidden: false,
  			edit_task_success: false,
            error: false,
            errors: {}
  		}
  	},
  	mounted() {
	  	this.getTask();
	  	this.getUsers();
	},
  	methods: {
  		editTask() {
  			var app = this;
  			this.axios.put('tasks/' + this.$route.params.id, {
	  			title: app.task.title,
	  			description: app.task.description,
	  			deadline: app.task.deadline,
	  			immediate: app.task.immediate,
	  			drafts: app.task.drafts,
	  			canceled: app.task.canceled,
	  			opened: app.task.opened,
	  			responsible: app.responsible
	  		}).then(response => {
	  			app.task = response.data.data;
	  			app.edit_task_success = true;
	  			app.isHidden = false;
            }).catch(error => {
                app.error = true;
                app.errors = error.data;
            });  
  		},
	  	getTask() {
  			var app = this;
	  		this.axios.get('tasks/' + this.$route.params.id).then(response => {
	  			app.task = response.data.data;
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
	    },
	  	deleteTask() {
  			var app = this;
	  		this.axios.delete('tasks/' + this.$route.params.id).then(response => {
	  			window.location = response.data.redirect;
            }).catch(error => {
                app.error = true;
                app.errors = error.data;
            });  
	  	}
  	},
  	filters: {
  		boolToString(value) {
  			if (!value) return 'Нет';
  			return 'Да';
  		},
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
